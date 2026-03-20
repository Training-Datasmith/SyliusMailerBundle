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
namespace Sylius\Bundle\Mailer_Bundle\Dependency_Injection\Compiler;

use Symfony\Component\Dependency_Injection\Alias;
use Symfony\Component\Dependency_Injection\Compiler\Compiler_Pass_Interface;
use Symfony\Component\Dependency_Injection\Container_Builder;
abstract class Adapter_Pass implements Compiler_Pass_Interface
{
    public function process_adapters(Container_Builder $container, string $adapter_alias, string $default_adapter_id, array $adapters_with_dependency): void
    {
        foreach ($adapters_with_dependency as $adapter => $dependency) {
            if (!$container->has($dependency)) {
                $container->remove_definition($adapter);
            }
        }
        if ($container->has_alias($adapter_alias)) {
            return;
        }
        $default_adapters = array_keys($adapters_with_dependency);
        $default_adapters[] = $default_adapter_id;
        foreach ($default_adapters as $adapter) {
            if ($container->has_definition($adapter)) {
                $container->set_alias($adapter_alias, new Alias($adapter, true));
                return;
            }
        }
    }
}