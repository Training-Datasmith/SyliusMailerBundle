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

use Sylius\Component\Mailer\Renderer\Rendered_Email;
use Symfony\Contracts\Event_Dispatcher\Event;
class Email_Render_Event extends Event
{
    /**
     * @param string[] $recipients
     */
    public function __construct(protected Rendered_Email $rendered_email, protected array $recipients = [])
    {
    }
    public function get_rendered_email(): Rendered_Email
    {
        return $this->rendered_email;
    }
    public function set_rendered_email(Rendered_Email $rendered_email): void
    {
        $this->rendered_email = $rendered_email;
    }
    public function get_recipients(): array
    {
        return $this->recipients;
    }
    public function set_recipients(array $recipients): void
    {
        $this->recipients = $recipients;
    }
}