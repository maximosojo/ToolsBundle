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

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Definition;

/**
 * MaxtoanToolsExtension
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
class MaxtoanToolsExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {	
        $processor = new Processor();
        $configuration = new Configuration();
        
        $loaderYml = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loaderYml->load('services.yml');

        $config = $processor->processConfiguration($configuration, $configs);
        if ($config['paginator']['format_array']) {
            $container->setParameter('paginator_format_array', $config['paginator']['format_array']);
        }
        

        if($config['link_generator']['enable'] === true) {
            $loaderYml->load('link_generator.yml');
            $container->setParameter('maxtoan_tools.service.link_generator.color', $config['link_generator']['color']); 
        }

        if($config['table_prefix']['enable'] === true) {
            $tablePrefix = $config['table_prefix']['prefix'].$config['table_prefix']['prefix_separator'];
            $tableNameLowercase = $config['table_prefix']['name_lowercase'];
            $tablePrefixListerner = new Definition($config['table_prefix']['listerner_class']);
            $tablePrefixListerner
                    ->addArgument($tablePrefix)
                    ->addArgument($tableNameLowercase)
                    ->addTag('doctrine.event_subscriber')
                    ;
            $tablePrefixListerner->addMethodCall("setConfig",array($config['table_prefix']));
            $container->setDefinition('maxtoan_tools.table_prefix_subscriber', $tablePrefixListerner);
        }

        // Command for clean db
        $container->setParameter('maxtoan_tools.command.db_clean_truncate_entities', false);
        $container->setParameter('maxtoan_tools.command.db_clean_delete_entities', false);
        // Command for clean folders
        $container->setParameter('maxtoan_tools.command.folder_clean_clean_paths', false);
        if(isset($config['command'])) {
            if(isset($config['command']['db']['clean']['truncate_entities'])) {
                $container->setParameter('maxtoan_tools.command.db_clean_truncate_entities', $config['command']['db']['clean']['truncate_entities']);
            }
            if(isset($config['command']['db']['clean']['delete_entities'])) {
                $container->setParameter('maxtoan_tools.command.db_clean_delete_entities', $config['command']['db']['clean']['delete_entities']);
            }
            if(isset($config['command']['folder']['clean']['clean_paths'])) {
                $container->setParameter('maxtoan_tools.command.folder_clean_clean_paths', $config['command']['folder']['clean']['clean_paths']);
            }
        }
        
        $container->setParameter('maxtoan_tools.service.link_generator.enable', $config['link_generator']['enable']);
        $container->setParameter('maxtoan_tools.loading.color', $config['loading']['color']);

        if($config['jms_serializer']['enable'] === true) {
            $loaderYml->load('jms_serializer.yml');
        }
    }
}