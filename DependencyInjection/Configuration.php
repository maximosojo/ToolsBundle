<?php

/*
 * This file is part of the Maxtoan Tools package.
 * 
 * (c) https://maxtoan.github.io/tools-bundle
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maxtoan\ToolsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Maxtoan\ToolsBundle\Model\Paginator\Paginator;

/**
 * This class contains the configuration information for the bundle.
 *
 * This information is solely responsible for how the different configuration
 * sections are normalized, and merged.
 *
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('maxtoan_tools', 'array');

        $rootNode
            ->children()
                ->arrayNode('loading')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('color')->defaultValue("#000")->end()
                    ->end()
                ->end()
                ->arrayNode('paginator')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('enable')->defaultFalse()->end()
                        ->scalarNode('format_array')->defaultValue(Paginator::FORMAT_ARRAY_DEFAULT)->end()
                    ->end()
                ->end()
                ->arrayNode('link_generator')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('enable')->defaultFalse()->end()
                        ->scalarNode('color')->defaultValue("#000")->end()
                    ->end()
                ->end()
                ->arrayNode('table_prefix')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('name_lowercase')->defaultFalse()->end()
                        ->booleanNode('enable')->defaultFalse()->end()
                        ->scalarNode('prefix')->defaultValue("prefix")->end()
                        ->scalarNode('prefix_separator')->defaultValue('_')->cannotBeEmpty()->end()
                        ->scalarNode('on_delete')->defaultNull()->end()
                        ->scalarNode('listerner_class')->defaultValue('Maxtoan\ToolsBundle\EventListener\TablePrefixListerner')->cannotBeEmpty()->end()
                    ->end()
                ->end()
            ;

        return $treeBuilder;
    }
}
