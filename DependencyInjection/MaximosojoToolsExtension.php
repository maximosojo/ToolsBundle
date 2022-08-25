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

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Alias;

/**
 * MaximosojoToolsExtension
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
class MaximosojoToolsExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {	
        $processor = new Processor();
        $configuration = new Configuration();
        
        $loaderYml = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loaderYml->load('services.yml');
        $loaderYml->load('commands.yml');
        
        $config = $processor->processConfiguration($configuration, $configs);
        if ($config['paginator']['format_array']) {
            $container->setParameter('paginator_format_array', $config['paginator']['format_array']);
        }
        

        if($config['link_generator']['enabled'] === true) {
            $loaderYml->load('link_generator.yml');
            $container->setParameter('maximosojo_tools.service.link_generator.color', $config['link_generator']['color']); 
        }

        if($config['search_manager']['enabled'] === true) {
            $loaderYml->load('services/search_manager.yml');
            $container->setParameter('maximosojo_tools.service.search_manager.icons.clean', $config['search_manager']['icons']['clean']); 
        }

        if($config['mailer']['enabled'] === true) {
            $loaderYml->load('mailer.yml');
            $container->setParameter("maximosojo_tools.symfonymailer.mailer_template_class", $config['mailer']["mailer_template_class"]);
            $container->setParameter("maximosojo_tools.symfonymailer.mailer_component_class", $config['mailer']["mailer_component_class"]);
            $container->setParameter("maximosojo_tools.symfonymailer.mailer_repository_manager", $config['mailer']["mailer_repository_manager"]);
            $idManager = $container->getParameter("maximosojo_tools.symfonymailer.mailer_repository_manager");
            $container->setAlias("maximosojo_tools.repository.mailer.em",$idManager);
        }

        // Notifier
        $enabled = $config['notifier']['texter']['enabled'];
        $container->setParameter("maximosojo_tools.notifier.texter.enabled", $enabled);
        if($enabled === true) {
            $transports = $config['notifier']['texter']["transports"];
            $loaderYml->load('services/notifier/texter_manager.yml');
            $container->setParameter("maximosojo_tools.notifier.texter.class", $config['notifier']['texter']["class"]);
            $container->setParameter("maximosojo_tools.notifier.texter.transports", $transports);
            // Dummy
            $container->setParameter("maximosojo_tools.notifier.texter.transports.dummy.enabled", $transports["dummy"]["enabled"]);
            // Twilio
            $container->setParameter("maximosojo_tools.notifier.texter.transports.twilio.enabled", $transports["twilio"]["enabled"]);
            $container->setParameter("maximosojo_tools.notifier.texter.transports.twilio.dsn", $transports["twilio"]["dsn"]);
        }

        // Table prefix
        if($config['table_prefix']['enabled'] === true) {
            $tablePrefix = $config['table_prefix']['prefix'].$config['table_prefix']['prefix_separator'];
            $tableNameLowercase = $config['table_prefix']['name_lowercase'];
            $tablePrefixListerner = new Definition($config['table_prefix']['listerner_class']);
            $tablePrefixListerner
                    ->addArgument($tablePrefix)
                    ->addArgument($tableNameLowercase)
                    ->addTag('doctrine.event_subscriber')
                    ;
            $tablePrefixListerner->addMethodCall("setConfig",array($config['table_prefix']));
            $container->setDefinition('maximosojo_tools.table_prefix_subscriber', $tablePrefixListerner);
        }

        // Command for clear db
        $container->setParameter('maximosojo_tools.command.db_clear_truncate_entities', false);
        $container->setParameter('maximosojo_tools.command.db_clear_delete_entities', false);
        // Command for clear folders
        $container->setParameter('maximosojo_tools.command.folder_clear_clear_paths', false);
        if(isset($config['command'])) {
            if(isset($config['command']['database']['clear']['truncate_entities'])) {
                $container->setParameter('maximosojo_tools.command.db_clear_truncate_entities', $config['command']['database']['clear']['truncate_entities']);
            }
            if(isset($config['command']['database']['clear']['delete_entities'])) {
                $container->setParameter('maximosojo_tools.command.db_clear_delete_entities', $config['command']['database']['clear']['delete_entities']);
            }
            if(isset($config['command']['folder']['clear']['clear_paths'])) {
                $container->setParameter('maximosojo_tools.command.folder_clear_clear_paths', $config['command']['folder']['clear']['clear_paths']);
            }
        }
        
        $container->setParameter('maximosojo_tools.service.link_generator.enabled', $config['link_generator']['enabled']);
        $container->setParameter('maximosojo_tools.loading.color', $config['loading']['color']);

        if($config['jms_serializer']['enabled'] === true) {
            $loaderYml->load('jms_serializer.yml');
        }

        // Manejador de estadisticas habilitado
        $container->setParameter('maximosojo_tools.object_manager.statistic.enabled',$config['object_manager']['statistics_manager']['enabled']);
        if($config['object_manager']['statistics_manager']['enabled'] === true){
            $loaderYml->load('services/object-manager/statistics_manager.yml');
            $container->setParameter('maximosojo_tools.object_manager.statistic',$config['object_manager']['statistics_manager']);
            $container->setParameter('maximosojo_tools.object_manager.history',$config['object_manager']['history_manager']);
        }

        // Manejador de configuraciones habilitado
        $container->setParameter('maximosojo_tools.object_manager.history.enabled',$config['object_manager']['history_manager']['enabled']);
        if($config['object_manager']['history_manager']['enabled'] === true){
            $loaderYml->load('services/object-manager/history_manager.yml');
            $container->setParameter('maximosojo_tools.object_manager.history',$config['object_manager']['history_manager']);
        }

        $container->setParameter('maximosojo_tools.object_manager.document.enabled',$config['object_manager']['document_manager']['enabled']);
        if($config['object_manager']['document_manager']['enabled'] === true){
            $loaderYml->load('services/object-manager/document_manager.yml');
        }

        $container->setParameter('maximosojo_tools.object_manager.exporter.enabled',$config['object_manager']['exporter_manager']['enabled']);
        if($config['object_manager']['exporter_manager']['enabled'] === true){
            $loaderYml->load('services/object-manager/exporter_manager.yml');
        }
        
        // Carga el manejador de objetos
        $loaderYml->load('services/object_manager.yml');
        $container->setAlias('maximosojo_tools.object_manager', new Alias($config['object_manager']['manager'], true));
        unset($config['object_manager']["manager"]);
        $container->setParameter('maximosojo_tools.object_manager.options',$config['object_manager']);

        // Revisa los componentes
        if(($componentsConfig = $config['component']) !== null) {
            if ($componentsConfig["liform"]["enabled"] === true) {
                $loaderYml->load('services/component/liform/transformers.yml');   
            }
        }
    }
}