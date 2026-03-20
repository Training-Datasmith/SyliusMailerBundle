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

use Sylius\Component\Mailer\Factory\Email_Factory;
use Sylius\Component\Mailer\Factory\Email_Factory_Interface;
return static function (Container_Configurator $container): void {
    $services = $container->services();
    $services->defaults()->public();
    $services->set('sylius.factory.email', Email_Factory::class);
    $services->alias(Email_Factory_Interface::class, 'sylius.factory.email');
};