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

use Sylius\Component\Mailer\Provider\Default_Settings_Provider;
use Sylius\Component\Mailer\Provider\Default_Settings_Provider_Interface;
use Sylius\Component\Mailer\Provider\Email_Provider;
use Sylius\Component\Mailer\Provider\Email_Provider_Interface;
return static function (Container_Configurator $container): void {
    $services = $container->services();
    $services->defaults()->public();
    $services->set('sylius.email_provider', Email_Provider::class)->args([service('sylius.factory.email'), '%sylius.mailer.emails%']);
    $services->alias(Email_Provider_Interface::class, 'sylius.email_provider');
    $services->set('sylius.mailer.default_settings_provider', Default_Settings_Provider::class)->args(['%sylius.mailer.sender_name%', '%sylius.mailer.sender_address%']);
    $services->alias(Default_Settings_Provider_Interface::class, 'sylius.mailer.default_settings_provider');
};