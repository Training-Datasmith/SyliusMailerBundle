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
namespace Sylius\Component\Mailer\Provider;

final readonly class Default_Settings_Provider implements Default_Settings_Provider_Interface
{
    public function __construct(private string $sender_name, private string $sender_address)
    {
    }
    public function get_sender_name(): string
    {
        return $this->sender_name;
    }
    public function get_sender_address(): string
    {
        return $this->sender_address;
    }
}