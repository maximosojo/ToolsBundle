<?php

/*
 * This file is part of the Maximosojo Tools package.
 * 
 * (c) https://maximosojo.github.io/tools-bundle
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maximosojo\ToolsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Maximosojo\ToolsBundle\Model\Paginator\Paginator;

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
        $treeBuilder = new TreeBuilder('maximosojo_tools');

        if (method_exists($treeBuilder, 'getRootNode')) {
            $rootNode = $treeBuilder->getRootNode();
        } else {
            // BC layer for symfony/config 4.1 and older
            $rootNode = $treeBuilder->root('maximosojo_tools', 'array');
        }

        $rootNode
            ->children()
                ->arrayNode('command')
                    ->children()
                        ->arrayNode('database')
                            ->children()
                                ->arrayNode('clear')
                                    ->children()
                                        ->arrayNode('truncate_entities')
                                            ->prototype('scalar')->end()
                                        ->end()
                                        ->arrayNode('delete_entities')
                                            ->prototype('scalar')->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('folder')
                            ->children()
                                ->arrayNode('clear')
                                    ->children()
                                        ->arrayNode('clear_paths')
                                            ->prototype('scalar')->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
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
                ->arrayNode('search_manager')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('enable')->defaultFalse()->end()
                        ->arrayNode('icons')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('clean')->defaultValue("ico ico-trash")->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('mailer')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('enable')->defaultFalse()->end()
                        ->scalarNode('mailer_template_class')->defaultNull()->end()
                        ->scalarNode('mailer_component_class')->defaultNull()->end()
                        ->scalarNode('mailer_repository_manager')->defaultValue("doctrine.orm.default_entity_manager")->end()
                    ->end()
                ->end()
                ->arrayNode('sms_manager')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('enable')->defaultFalse()->end()
                        ->scalarNode('message_class')->defaultNull()->end()
                        ->booleanNode('disable_delivery')->defaultFalse()->end()
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
                        ->scalarNode('listerner_class')->defaultValue('Maximosojo\ToolsBundle\EventListener\TablePrefixListerner')->cannotBeEmpty()->end()
                    ->end()
                ->end()
                ->arrayNode('jms_serializer')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('enable')->defaultFalse()->end()
                    ->end()
                ->end()
                ->arrayNode('component')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('liform')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->booleanNode('enable')->defaultFalse()->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('object_manager')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('manager')->defaultValue('maximosojo_tools.object_manager.default')->end()
                        ->arrayNode('document_manager')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->booleanNode('enable')->defaultFalse()->end()
                                ->scalarNode('template')->defaultValue("MaximosojoToolsBundle:objectManager:documentManager/documents.html.twig")->end()
                                ->scalarNode('adapter')->defaultValue("maximosojo_tools.document_manager_disk_adapter")->end()
                            ->end()
                        ->end()
                        ->arrayNode('exporter_manager')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->booleanNode('enable')->defaultFalse()->end()
                                ->scalarNode('template')->defaultValue("MaximosojoToolsBundle:objectManager:exporterManager/documents.html.twig")->end()
                                ->scalarNode('adapter')->defaultValue("maximosojo_tools.exporter_manager_doctrine_orm_adapter")->end()
                                ->arrayNode('chaines')
                                    ->prototype('array')
                                        ->children()
                                            ->scalarNode('chain')->defaultNull()->end()
                                            ->scalarNode('class')->defaultNull()->end()
                                            ->arrayNode('templates')
                                                ->prototype('array')
                                                    ->children()
                                                        ->scalarNode('id')->defaultNull()->end()
                                                        ->scalarNode('label')->defaultNull()->end()
                                                    ->end()
                                                ->end()
                                            ->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('history_manager')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->booleanNode('enable')->defaultFalse()->end()
                                ->scalarNode('adapter')->defaultNull()->end()
                            ->end()
                        ->end()
                        ->arrayNode('statistics_manager')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->booleanNode('enable')->defaultFalse()->end()
                                ->scalarNode('adapter')->defaultValue("maximosojo_tools.statistics_manager_doctrine_orm_adapter")->end()
                                ->arrayNode('object_types')
                                    ->prototype('array')
                                        ->children()
                                            ->scalarNode('objectType')->defaultNull()->end()
                                            ->scalarNode('adapter')->defaultNull()->end()
                                            ->arrayNode('objectValids')
                                                ->defaultValue(array())
                                                ->prototype('scalar')
                                            ->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ;

        return $treeBuilder;
    }
}
