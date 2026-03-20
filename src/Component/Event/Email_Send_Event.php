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
namespace Sylius\Component\Mailer\Event;

use Sylius\Component\Mailer\Model\Email_Interface;
use Symfony\Contracts\Event_Dispatcher\Event;
final class Email_Send_Event extends Event
{
    /**
     * @param string[] $recipients
     * @param string[] $replyTo
     */
    public function __construct(protected mixed $message, protected Email_Interface $email, protected array $data, protected array $recipients = [], protected array $reply_to = [])
    {
    }
    public function get_recipients(): array
    {
        return $this->recipients;
    }
    public function get_email(): Email_Interface
    {
        return $this->email;
    }
    public function get_message(): mixed
    {
        return $this->message;
    }
    public function get_data(): array
    {
        return $this->data;
    }
    /**
     * @return string[]
     */
    public function get_reply_to(): array
    {
        return $this->reply_to;
    }
}