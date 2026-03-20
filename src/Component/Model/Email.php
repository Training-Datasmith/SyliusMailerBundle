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
namespace Sylius\Component\Mailer\Model;

final class Email implements Email_Interface
{
    private string|int|null $id = null;
    private ?string $code = null;
    private bool $enabled = true;
    private ?string $subject = null;
    private ?string $content = null;
    private ?string $template = null;
    private ?string $sender_name = null;
    private ?string $sender_address = null;
    public function get_id(): string|int|null
    {
        return $this->id;
    }
    public function get_code(): ?string
    {
        return $this->code;
    }
    public function set_code(string $code): void
    {
        $this->code = $code;
    }
    public function is_enabled(): bool
    {
        return $this->enabled;
    }
    public function set_enabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }
    public function enable(): void
    {
        $this->enabled = true;
    }
    public function disable(): void
    {
        $this->enabled = false;
    }
    public function get_subject(): ?string
    {
        return $this->subject;
    }
    public function set_subject(string $subject): void
    {
        $this->subject = $subject;
    }
    public function get_content(): ?string
    {
        return $this->content;
    }
    public function set_content(string $content): void
    {
        $this->content = $content;
    }
    public function get_template(): ?string
    {
        return $this->template;
    }
    public function set_template(string $template): void
    {
        $this->template = $template;
    }
    public function get_sender_name(): ?string
    {
        return $this->sender_name;
    }
    public function set_sender_name(string $sender_name): void
    {
        $this->sender_name = $sender_name;
    }
    public function get_sender_address(): ?string
    {
        return $this->sender_address;
    }
    public function set_sender_address(string $sender_address): void
    {
        $this->sender_address = $sender_address;
    }
}