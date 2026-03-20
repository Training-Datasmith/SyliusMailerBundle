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

use Sylius\Bundle\Mailer_Bundle\Sender\Adapter\Default_Adapter;
use Sylius\Bundle\Mailer_Bundle\Sender\Adapter\Symfony_Mailer_Adapter;
use Sylius\Component\Mailer\Modifier\Email_Modifier_Interface;
use Sylius\Component\Mailer\Sender\Adapter\Abstract_Adapter;
use Sylius\Component\Mailer\Sender\Sender;
use Sylius\Component\Mailer\Sender\Sender_Interface;
return static function (Container_Configurator $container): void {
    $services = $container->services();
    $services->defaults()->public();
    $services->set('sylius.email_sender', Sender::class)->args([service('sylius.email_renderer.adapter'), service('sylius.email_sender.adapter'), service('sylius.email_provider'), service('sylius.mailer.default_settings_provider'), service(Email_Modifier_Interface::class)]);
    $services->alias(Sender_Interface::class, 'sylius.email_sender');
    $services->set('sylius.email_sender.adapter.abstract', Abstract_Adapter::class)->abstract()->call('setEventDispatcher', [service('event_dispatcher')->ignore_on_invalid()]);
    $services->set('sylius.email_sender.adapter.default', Default_Adapter::class)->parent('sylius.email_sender.adapter.abstract')->public();
    $services->set('sylius.email_sender.adapter.symfony_mailer', Symfony_Mailer_Adapter::class)->parent('sylius.email_sender.adapter.abstract')->public()->args([service('mailer.mailer')]);
};