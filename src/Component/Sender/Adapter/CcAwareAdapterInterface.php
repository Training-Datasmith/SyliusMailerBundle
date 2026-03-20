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
namespace Sylius\Component\Mailer\Sender\Adapter;

use Sylius\Component\Mailer\Model\Email_Interface;
use Sylius\Component\Mailer\Renderer\Rendered_Email;
interface Cc_Aware_Adapter_Interface extends Adapter_Interface
{
    /**
     * @param string[] $recipients A list of email addresses to receive the message.
     * @param string[] $attachments A list of file paths to attach to the message.
     * @param string[] $replyTo A list of email addresses to set as the Reply-To address for the message.
     * @param string[] $ccRecipients A list of email addresses set as carbon copy
     * @param string[] $bccRecipients A list of email addresses set as blind carbon copy
     */
    public function send_with_cc(array $recipients, string $sender_address, string $sender_name, Rendered_Email $rendered_email, Email_Interface $email, array $data, array $attachments = [], array $reply_to = [], array $cc_recipients = [], array $bcc_recipients = []): void;
}