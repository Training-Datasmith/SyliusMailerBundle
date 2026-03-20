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

use Symfony\Component\Dependency_Injection\Container_Builder;
final class Renderer_Adapter_Pass extends Adapter_Pass
{
    public const ADAPTER_ALIAS = 'sylius.email_renderer.adapter';
    public const DEFAULT_ADAPTER = 'sylius.email_renderer.adapter.default';
    public function process(Container_Builder $container): void
    {
        $this->process_adapters($container, self::ADAPTER_ALIAS, self::DEFAULT_ADAPTER, ['sylius.email_renderer.adapter.twig' => 'twig']);
    }
}