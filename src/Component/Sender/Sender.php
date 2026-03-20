<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare (strict_types=1);
namespace Sylius\Component\Mailer\Sender;

use Sylius\Component\Mailer\Modifier\Email_Modifier_Interface;
use Sylius\Component\Mailer\Provider\Default_Settings_Provider_Interface;
use Sylius\Component\Mailer\Provider\Email_Provider_Interface;
use Sylius\Component\Mailer\Renderer\Adapter\Adapter_Interface as RendererAdapterInterface;
use Sylius\Component\Mailer\Sender\Adapter\Adapter_Interface as SenderAdapterInterface;
use Sylius\Component\Mailer\Sender\Adapter\Cc_Aware_Adapter_Interface;
use Webmozart\Assert\Assert;
/**
 * Orchestrates email rendering, optional modification, and sending.
 *
 * Composes the renderer adapter, sender adapter, email provider, and optional
 * modifier into a single send() operation with event-based extensibility.
 */
final readonly class Sender implements Sender_Interface
{
    /**
     * Creates the sender with all required collaborators.
     *
     * @param Renderer_Adapter_Interface         $renderer_adapter        Renders email templates to HTML/text
     * @param Sender_Adapter_Interface           $sender_adapter          Delivers rendered emails via a transport
     * @param Email_Provider_Interface           $provider                Loads Email model instances by code
     * @param Default_Settings_Provider_Interface $default_settings_provider Fallback from-address and from-name
     * @param Email_Modifier_Interface|null       $email_modifier          Optional modifier chain applied before rendering
     *
     * @deprecated Not passing $email_modifier is deprecated since 2.1 and will be required in 3.0
     */
    public function __construct(
        private Renderer_Adapter_Interface $renderer_adapter,
        private Sender_Adapter_Interface $sender_adapter,
        private Email_Provider_Interface $provider,
        private Default_Settings_Provider_Interface $default_settings_provider,
        private ?Email_Modifier_Interface $email_modifier = null,
    ) {
        if ($this->email_modifier === null) {
            @trigger_error('Not passing EmailModifierInterface is deprecated since 2.1 and will not be possible in 3.0');
        }
    }

    /**
     * Renders and sends an email identified by code to one or more recipients.
     *
     * If the email is disabled in configuration this method returns without sending.
     * When more than 5 arguments are passed and the adapter implements
     * Cc_Aware_Adapter_Interface, arguments 6 and 7 are forwarded as CC and BCC lists.
     *
     * @param string   $code        Email code identifying the template and settings to use
     * @param string[] $recipients  Non-empty list of recipient email addresses
     * @param array    $data        Template variables passed to the renderer
     * @param array    $attachments File attachments to include in the email
     * @param string[] $reply_to    Reply-to email addresses
     *
     * @return void
     *
     * @throws \InvalidArgumentException If any recipient is an empty string
     */
    public function send(string $code, array $recipients, array $data = [], array $attachments = [], array $reply_to = []): void
    {
        $arguments = func_get_args();
        Assert::all_string_not_empty($recipients);
        $email = $this->provider->get_email($code);
        if ($this->email_modifier !== null) {
            $email = $this->email_modifier->modify($email, $data);
        }
        if (!$email->is_enabled()) {
            return;
        }
        $sender_address = $email->get_sender_address() ?: $this->default_settings_provider->get_sender_address();
        $sender_name = $email->get_sender_name() ?: $this->default_settings_provider->get_sender_name();
        $rendered_email = $this->renderer_adapter->render($email, $data);
        if (count($arguments) > 5 && $this->sender_adapter instanceof Cc_Aware_Adapter_Interface) {
            /** @var array<string> $ccRecipients */
            $cc_recipients = $arguments[5] ?? [];
            /** @var array<string> $bccRecipients */
            $bcc_recipients = $arguments[6] ?? [];
            $this->sender_adapter->send_with_cc($recipients, $sender_address, $sender_name, $rendered_email, $email, $data, $attachments, $reply_to, $cc_recipients, $bcc_recipients);
            return;
        }
        $this->sender_adapter->send($recipients, $sender_address, $sender_name, $rendered_email, $email, $data, $attachments, $reply_to);
    }
}