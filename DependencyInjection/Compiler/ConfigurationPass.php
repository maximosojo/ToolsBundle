<?php

/*
 * This file is part of the Maxtoan Tools package.
 * 
 * (c) https://maximosojo.github.io/tools-bundle
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maxtoan\ToolsBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use ReflectionClass;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Agrega todos los wrapper al servicio de configuracion
 *
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
class ConfigurationPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $debug = $container->getParameter('kernel.debug');
        $configurationManagerClass = "Maxtoan\ToolsBundle\Service\Core\Configuration\ConfigurationManager";
        $configurationManager = new Definition($configurationManagerClass, [
            new Reference("configuration.adapter.orm"), [
                "add_default_wrapper" => true,
                "debug" => $debug,
            ]
        ]);
        $configurationManager->setPublic(true);
        
        $configurationManagerNameService = "maxtoan_tools.manager.configuration";
        $container->setDefinition($configurationManagerNameService, $configurationManager);
        $container->setParameter('maxtoan_tools.configuration_manager.name', $configurationManagerNameService);

        $manager = $container->getDefinition($container->getParameter("maxtoan_tools.configuration_manager.name"));
        $tags = $container->findTaggedServiceIds('configuration.wrapper');
        foreach ($tags as $id => $params) {
            $definition = $container->findDefinition($id);
            $manager->addMethodCall("addWrapper", array($definition));
        }

        // Registra manejador de objetos
        $config = $container->getParameter("maxtoan_tools.object_manager.options");

        // Manejador de estadísticas
        if ($container->getParameter('maxtoan_tools.object_manager.statistic.enable') === true) {
            $statistic = $container->getParameter("maxtoan_tools.object_manager.statistic");
            $idStatisticManagerAdapter = $statistic["adapter"];
            if ($idStatisticManagerAdapter && $container->hasDefinition($idStatisticManagerAdapter)) {
                $adapterDefinition = $container->findDefinition($idStatisticManagerAdapter);
                $statisticManagerDefinition = $container->findDefinition("maxtoan_tools.statistics_manager.default");
                $statisticManagerDefinition->addArgument($adapterDefinition);                
            }

            $statisticManager = $container->getDefinition("maxtoan_tools.statistics_manager.default"); 
            foreach ($statistic["object_types"] as $param) {
                if ($param["adapter"]) {
                    $statisticManager->addMethodCall("addAdapter", [$container->getDefinition($param["adapter"]),$param["objectType"]]);
                }
                if ($param["objectValids"]) {
                    $statisticManager->addMethodCall("addObjectValids", [$param["objectType"],$param["objectValids"]]);
                }
            }
        }

        // Manejador de Historial
        if ($container->getParameter('maxtoan_tools.object_manager.history.enable') === true) {
            $history = $container->getParameter("maxtoan_tools.object_manager.history");
            $idHistoryManagerAdapter = $history["adapter"];
            if ($idHistoryManagerAdapter && $container->hasDefinition($idHistoryManagerAdapter)) {
                $adapterDefinition = $container->findDefinition($idHistoryManagerAdapter);
                $historyManagerDefinition = $container->findDefinition("maxtoan_tools.history_manager.default");
                $historyManagerDefinition->addArgument($adapterDefinition);
            }
        }

        // Manejador de documentos
        if ($container->getParameter('maxtoan_tools.object_manager.document.enable') === true) {
            // Manejador de documentos
            $adapterDefinition = $container->findDefinition($config["document_manager"]["adapter"]);
            $documentManager = $container->findDefinition("maxtoan_tools.document_manager.default");
            $documentManager->addArgument($adapterDefinition);
        }

        // Manejador de exportaciones
        if ($container->getParameter('maxtoan_tools.object_manager.exporter.enable') === true) {
            $adapterDefinition = $container->findDefinition($config["exporter_manager"]["adapter"]);
            $exporterManager = $container->getDefinition("maxtoan_tools.exporter_manager.default");
            $exporterManager->addArgument($documentManager);
            $exporterManager->addArgument($config["exporter_manager"]);
            $exporterManager->addMethodCall("setAdapter", array($adapterDefinition));
            // Registro de chaines soportados
            $chaines = $config["exporter_manager"]["chaines"];
            foreach ($chaines as $key => $chain) {
                $exporterManager->addMethodCall("addChainModel", [$chain]);
            }

            // Registra adaptador en el servicio de plantillas
            $templateService = $container->getDefinition("maxtoan_tools.template_service");
            $adapters = $container->findTaggedServiceIds("exporter.template.adapter");
            foreach ($adapters as $id => $adapter) {
                $templateService->addMethodCall("addAdapter", [$container->getDefinition($id)]);
            }
        }
    }
}
