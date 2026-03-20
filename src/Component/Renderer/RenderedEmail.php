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
namespace Sylius\Component\Mailer\Renderer;

class Rendered_Email
{
    public function __construct(protected string $subject, protected string $body)
    {
    }
    public function get_subject(): string
    {
        return $this->subject;
    }
    public function set_subject(string $subject): void
    {
        $this->subject = $subject;
    }
    public function get_body(): string
    {
        return $this->body;
    }
    public function set_body(string $body): void
    {
        $this->body = $body;
    }
}