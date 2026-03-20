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
namespace Sylius\Bundle\Mailer_Bundle\Renderer\Adapter;

use Sylius\Component\Mailer\Model\Email_Interface;
use Sylius\Component\Mailer\Renderer\Adapter\Abstract_Adapter;
use Sylius\Component\Mailer\Renderer\Rendered_Email;
final class Email_Default_Adapter extends Abstract_Adapter
{
    public function render(Email_Interface $email, array $data = []): Rendered_Email
    {
        throw new \RuntimeException(sprintf('You need to configure an adapter to render the email. Take a look at %s (requires "symfony/twig-bundle" library).', Email_Twig_Adapter::class));
    }
}