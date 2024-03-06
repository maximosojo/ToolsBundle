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
                        ->booleanNode('enabled')->defaultFalse()->end()
                        ->scalarNode('format_array')->defaultValue(Paginator::FORMAT_ARRAY_DEFAULT)->end()
                    ->end()
                ->end()
                ->arrayNode('link_generator')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('enabled')->defaultFalse()->end()
                        ->scalarNode('color')->defaultValue("#000")->end()
                    ->end()
                ->end()
                ->arrayNode('notifier')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('texter')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->booleanNode('enabled')->defaultFalse()->end()
                                ->scalarNode('class')->defaultNull()->end()
                                ->arrayNode('transports')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->arrayNode('dummy')
                                            ->addDefaultsIfNotSet()
                                            ->children()
                                                ->booleanNode('enabled')->defaultFalse()->end()
                                            ->end()
                                        ->end()
                                        ->arrayNode('interconectados')
                                            ->addDefaultsIfNotSet()
                                            ->children()
                                                ->booleanNode('enabled')->defaultFalse()->end()
                                                ->scalarNode('dsn')->defaultValue("user:password")->end()
                                            ->end()
                                        ->end()
                                        ->arrayNode('twilio')
                                            ->addDefaultsIfNotSet()
                                            ->children()
                                                ->booleanNode('enabled')->defaultFalse()->end()
                                                ->scalarNode('dsn')->defaultValue("sid:token:number")->end()
                                            ->end()
                                        ->end()
                                    ->end()        
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('mailer')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->booleanNode('enabled')->defaultFalse()->end()
                                ->scalarNode('template_class')->defaultNull()->end()
                                ->scalarNode('component_class')->defaultNull()->end()
                                ->scalarNode('queue_class')->defaultNull()->end()
                                ->scalarNode('repository_manager')->defaultValue("doctrine.orm.default_entity_manager")->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('table_prefix')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('name_lowercase')->defaultFalse()->end()
                        ->booleanNode('enabled')->defaultFalse()->end()
                        ->scalarNode('prefix')->defaultValue("prefix")->end()
                        ->scalarNode('prefix_separator')->defaultValue('_')->cannotBeEmpty()->end()
                        ->scalarNode('on_delete')->defaultNull()->end()
                        ->scalarNode('listerner_class')->defaultValue('Maximosojo\ToolsBundle\EventListener\TablePrefixListerner')->cannotBeEmpty()->end()
                    ->end()
                ->end()
                ->arrayNode('jms_serializer')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('enabled')->defaultFalse()->end()
                    ->end()
                ->end()
                ->arrayNode('component')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('liform')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->booleanNode('enabled')->defaultFalse()->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('option_manager')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->booleanNode('enabled')->defaultFalse()->end()
                            ->booleanNode('debug')->end()
                            ->scalarNode('manager')->defaultValue('maximosojo_tools.option_manager.default')->cannotBeEmpty()->end()
                            ->scalarNode('adapter')->defaultValue('maximosojo_tools.option_manager.adapter.orm')->cannotBeEmpty()->end()
                            ->scalarNode('cache')->defaultValue('maximosojo_tools.option_manager.cache.disk')->cannotBeEmpty()->end()
                    ->end()
                ->end()
                ->arrayNode('object_manager')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('manager')->defaultValue('maximosojo_tools.object_manager.default')->end()
                        ->arrayNode('document_manager')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->booleanNode('enabled')->defaultFalse()->end()
                                ->scalarNode('template')->defaultValue("MaximosojoToolsBundle:objectManager:documentManager/documents.html.twig")->end()
                                ->scalarNode('adapter')->defaultValue("maximosojo_tools.document_manager_disk_adapter")->end()
                            ->end()
                        ->end()
                        ->arrayNode('exporter_manager')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->booleanNode('enabled')->defaultFalse()->end()
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
                                ->booleanNode('enabled')->defaultFalse()->end()
                                ->scalarNode('adapter')->defaultNull()->end()
                            ->end()
                        ->end()
                        ->arrayNode('statistics_manager')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->booleanNode('enabled')->defaultFalse()->end()
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
