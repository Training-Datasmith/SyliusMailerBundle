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
namespace Sylius\Component\Mailer\Renderer\Adapter;

use Sylius\Component\Mailer\Model\Email_Interface;
use Sylius\Component\Mailer\Renderer\Rendered_Email;
interface Adapter_Interface
{
    public function render(Email_Interface $email, array $data = []): Rendered_Email;
}