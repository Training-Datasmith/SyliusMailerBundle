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

use Sylius\Bundle\Mailer_Bundle\Renderer\Adapter\Email_Default_Adapter;
use Sylius\Bundle\Mailer_Bundle\Renderer\Adapter\Email_Twig_Adapter;
use Sylius\Component\Mailer\Renderer\Adapter\Abstract_Adapter;
return static function (Container_Configurator $container): void {
    $services = $container->services();
    $services->defaults()->public();
    $services->set('sylius.email_renderer.adapter.abstract', Abstract_Adapter::class)->abstract()->call('setEventDispatcher', [service('event_dispatcher')->ignore_on_invalid()]);
    $services->set('sylius.email_renderer.adapter.default', Email_Default_Adapter::class)->parent('sylius.email_renderer.adapter.abstract')->public();
    $services->set('sylius.email_renderer.adapter.twig', Email_Twig_Adapter::class)->parent('sylius.email_renderer.adapter.abstract')->public()->args([service('twig'), service('event_dispatcher')->null_on_invalid()]);
};