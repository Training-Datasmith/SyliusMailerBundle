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
namespace Symfony\Component\Dependency_Injection\Loader\Configurator;

use Sylius\Component\Mailer\Modifier\Composite_Email_Modifier;
use Sylius\Component\Mailer\Modifier\Email_Modifier_Interface;
return static function (Container_Configurator $container): void {
    $services = $container->services();
    $services->defaults()->public();
    $services->set(Email_Modifier_Interface::class, Composite_Email_Modifier::class)->args([tagged_iterator('sylius_mailer.email_modifier')]);
};