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

interface Email_Interface
{
    public function get_code(): ?string;
    public function set_code(string $code): void;
    public function is_enabled(): bool;
    public function set_enabled(bool $enabled): void;
    public function enable(): void;
    public function disable(): void;
    public function get_subject(): ?string;
    public function set_subject(string $subject): void;
    public function get_content(): ?string;
    public function set_content(string $content): void;
    public function get_template(): ?string;
    public function set_template(string $template): void;
    public function get_sender_name(): ?string;
    public function set_sender_name(string $sender_name): void;
    public function get_sender_address(): ?string;
    public function set_sender_address(string $sender_address): void;
}