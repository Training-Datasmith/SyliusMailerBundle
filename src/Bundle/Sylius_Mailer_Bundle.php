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
namespace Sylius\Bundle\Mailer_Bundle;

use Sylius\Bundle\Mailer_Bundle\Dependency_Injection\Compiler\Renderer_Adapter_Pass;
use Sylius\Bundle\Mailer_Bundle\Dependency_Injection\Compiler\Sender_Adapter_Pass;
use Symfony\Component\Dependency_Injection\Compiler\Pass_Config;
use Symfony\Component\Dependency_Injection\Container_Builder;
use Symfony\Component\Http_Kernel\Bundle\Bundle;
final class Sylius_Mailer_Bundle extends Bundle
{
    public function build(Container_Builder $container): void
    {
        parent::build($container);
        $container->add_compiler_pass(new Sender_Adapter_Pass(), Pass_Config::TYPE_BEFORE_OPTIMIZATION, -256);
        $container->add_compiler_pass(new Renderer_Adapter_Pass(), Pass_Config::TYPE_BEFORE_OPTIMIZATION, -256);
    }
}