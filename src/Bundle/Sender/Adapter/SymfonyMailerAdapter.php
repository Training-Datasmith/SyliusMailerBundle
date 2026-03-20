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
namespace Sylius\Bundle\Mailer_Bundle\Sender\Adapter;

use Egulias\Email_Validator\Email_Validator;
use Egulias\Email_Validator\Validation\Rfc_Validation;
use Sylius\Component\Mailer\Event\Email_Send_Event;
use Sylius\Component\Mailer\Model\Email_Interface;
use Sylius\Component\Mailer\Renderer\Rendered_Email;
use Sylius\Component\Mailer\Sender\Adapter\Abstract_Adapter;
use Sylius\Component\Mailer\Sender\Adapter\Cc_Aware_Adapter_Interface;
use Sylius\Component\Mailer\Sylius_Mailer_Events;
use Symfony\Component\Mailer\Mailer_Interface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Webmozart\Assert\Assert;
final class Symfony_Mailer_Adapter extends Abstract_Adapter implements Cc_Aware_Adapter_Interface
{
    public function __construct(private readonly Mailer_Interface $mailer)
    {
    }
    public function send(array $recipients, string $sender_address, string $sender_name, Rendered_Email $rendered_email, Email_Interface $email, array $data, array $attachments = [], array $reply_to = []): void
    {
        $this->send_message($rendered_email, $sender_address, $sender_name, $recipients, $reply_to, $attachments, $email, $data);
    }
    public function send_with_cc(array $recipients, string $sender_address, string $sender_name, Rendered_Email $rendered_email, Email_Interface $email, array $data, array $attachments = [], array $reply_to = [], array $cc_recipients = [], array $bcc_recipients = []): void
    {
        $this->send_message($rendered_email, $sender_address, $sender_name, $recipients, $reply_to, $attachments, $email, $data, $cc_recipients, $bcc_recipients);
    }
    private function send_message(Rendered_Email $rendered_email, string $sender_address, string $sender_name, array $recipients, array $reply_to, array $attachments, Email_Interface $email, array $data, array $cc_recipients = [], array $bcc_recipients = []): void
    {
        Assert::all_string_not_empty($recipients);
        Assert::all_string_not_empty($reply_to);
        $message = (new Email())->subject($rendered_email->get_subject())->from(new Address($sender_address, $sender_name))->to(...$this->format_recipients($recipients))->reply_to(...$reply_to)->html($rendered_email->get_body());
        $message->add_cc(...$this->format_recipients($cc_recipients));
        $message->add_bcc(...$this->format_recipients($bcc_recipients));
        foreach ($attachments as $attachment) {
            $message->attach_from_path($attachment);
        }
        $email_send_event = new Email_Send_Event($message, $email, $data, $recipients, $reply_to);
        $this->dispatcher?->dispatch($email_send_event, Sylius_Mailer_Events::EMAIL_PRE_SEND);
        $this->mailer->send($message);
        $this->dispatcher?->dispatch($email_send_event, Sylius_Mailer_Events::EMAIL_POST_SEND);
    }
    /**
     * There are two kinds of recipient array syntax that can be passed to the send method:
     * - Only email addresses: ['john.doe@mail.com', 'jane.smith@mail.com']
     * - Email addresses with names: ['john.doe@mail.com' => 'John Doe', 'jane.smith@mail.com' => 'Jane Smith']
     *
     * Since Symfony\Mailer requires an instance of Symfony\Component\Mime\Address or a valid email string for each recipient,
     * we need to transform the recipient array where keys are address and values are name to an array of Address object.
     */
    protected function format_recipients(array $recipients): array
    {
        $transformed_recipients = [];
        $validator = new Email_Validator();
        foreach ($recipients as $address_or_key => $name_or_address) {
            if (\is_string($address_or_key) && $validator->is_valid($address_or_key, new Rfc_Validation())) {
                $transformed_recipients[] = new Address($address_or_key, $name_or_address);
                continue;
            }
            $transformed_recipients[] = $name_or_address;
        }
        return $transformed_recipients;
    }
}