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

use Symfony\Component\Config\Definition\Builder\Array_Node_Definition;
use Symfony\Component\Config\Definition\Builder\Tree_Builder;
use Symfony\Component\Config\Definition\Configuration_Interface;
final class Configuration implements Configuration_Interface
{
    /**
     * {@inheritdoc}
     */
    public function get_config_tree_builder(): Tree_Builder
    {
        $tree_builder = new Tree_Builder('sylius_mailer');
        /** @var ArrayNodeDefinition $rootNode */
        $root_node = $tree_builder->get_root_node();
        $root_node->children()->scalar_node('sender_adapter')->end()->scalar_node('renderer_adapter')->end()->end();
        $this->add_emails_section($root_node);
        return $tree_builder;
    }
    private function add_emails_section(Array_Node_Definition $node): void
    {
        $node->children()->array_node('sender')->add_defaults_if_not_set()->children()->scalar_node('name')->default_value('Example.com Store')->end()->scalar_node('address')->default_value('no-reply@example.com')->end()->end()->end()->array_node('emails')->use_attribute_as_key('code')->array_prototype()->children()->scalar_node('subject')->set_deprecated('sylius/mailer-bundle', '1.5', 'The "subject" option is deprecated since SyliusMailerBundle 1.5')->end()->scalar_node('template')->cannot_be_empty()->end()->boolean_node('enabled')->default_true()->end()->array_node('sender')->children()->scalar_node('name')->end()->scalar_node('address')->end()->end()->end()->end()->end()->end()->array_node('templates')->set_deprecated('sylius/mailer-bundle', '1.6', 'The "templates" option is deprecated')->use_attribute_as_key('name')->scalar_prototype()->end()->end()->end();
    }
}