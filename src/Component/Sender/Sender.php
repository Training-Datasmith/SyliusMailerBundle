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
final readonly class Sender implements Sender_Interface
{
    public function __construct(private Renderer_Adapter_Interface $renderer_adapter, private Sender_Adapter_Interface $sender_adapter, private Email_Provider_Interface $provider, private Default_Settings_Provider_Interface $default_settings_provider, private ?Email_Modifier_Interface $email_modifier = null)
    {
        if ($this->email_modifier === null) {
            @trigger_error('Not passing EmailModifierInterface is deprecated since 2.1 and will not be possible in 3.0');
        }
    }
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