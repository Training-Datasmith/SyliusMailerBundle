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
namespace Sylius\Bundle\Mailer_Bundle\Dependency_Injection;

use Sylius\Bundle\Mailer_Bundle\Dependency_Injection\Compiler\Renderer_Adapter_Pass;
use Sylius\Bundle\Mailer_Bundle\Dependency_Injection\Compiler\Sender_Adapter_Pass;
use Symfony\Component\Config\File_Locator;
use Symfony\Component\Dependency_Injection\Alias;
use Symfony\Component\Dependency_Injection\Container_Builder;
use Symfony\Component\Dependency_Injection\Loader\Php_File_Loader;
use Symfony\Component\Http_Kernel\Dependency_Injection\Configurable_Extension;
final class Sylius_Mailer_Extension extends Configurable_Extension
{
    protected function load_internal(array $merged_config, Container_Builder $container): void
    {
        $loader = new Php_File_Loader($container, new File_Locator(__DIR__ . '/../Resources/config'));
        $loader->load('services.php');
        $this->configure_sender_adapter($merged_config, $container);
        $this->configure_renderer_adapter($merged_config, $container);
        $container->set_parameter('sylius.mailer.sender_name', $merged_config['sender']['name']);
        $container->set_parameter('sylius.mailer.sender_address', $merged_config['sender']['address']);
        $templates = $merged_config['templates'] ?? ['Default' => '@SyliusMailer/default.html.twig'];
        $container->set_parameter('sylius.mailer.emails', $merged_config['emails']);
        $container->set_parameter('sylius.mailer.templates', $templates);
    }
    private function configure_sender_adapter(array $merged_config, Container_Builder $container): void
    {
        if (isset($merged_config['sender_adapter'])) {
            $container->set_alias(Sender_Adapter_Pass::ADAPTER_ALIAS, new Alias($merged_config['sender_adapter'], true));
        }
    }
    private function configure_renderer_adapter(array $merged_config, Container_Builder $container): void
    {
        if (isset($merged_config['renderer_adapter'])) {
            $container->set_alias(Renderer_Adapter_Pass::ADAPTER_ALIAS, new Alias($merged_config['renderer_adapter'], true));
        }
    }
}