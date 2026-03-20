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

use Sylius\Component\Mailer\Model\Email_Interface;
use Sylius\Component\Mailer\Renderer\Rendered_Email;
use Sylius\Component\Mailer\Sender\Adapter\Abstract_Adapter;
use Symfony\Component\Event_Dispatcher\Event_Dispatcher_Interface;
final class Default_Adapter extends Abstract_Adapter
{
    public function __construct(?Event_Dispatcher_Interface $dispatcher = null)
    {
        $this->dispatcher = $dispatcher;
    }
    public function send(array $recipients, string $sender_address, string $sender_name, Rendered_Email $rendered_email, Email_Interface $email, array $data, array $attachments = [], array $reply_to = []): void
    {
        throw new \RuntimeException(sprintf('You need to configure an adapter to send the email. Take a look at %s (requires "symfony/mailer" library).', Symfony_Mailer_Adapter::class));
    }
    public function send_with_cc(array $recipients, string $sender_address, string $sender_name, Rendered_Email $rendered_email, Email_Interface $email, array $data, array $attachments = [], array $reply_to = [], array $cc_recipients = [], array $bcc_recipients = []): never
    {
        throw new \RuntimeException(sprintf('You need to configure an adapter to send the email. Take a look at %s (requires "symfony/mailer" library).', Symfony_Mailer_Adapter::class));
    }
}