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
                $statisticManagerDefinition = $container->findDefinition("maxtoan_tools.statistics_manager");
                $statisticManagerDefinition->addArgument($adapterDefinition);                
            }

            $statisticManager = $container->getDefinition("maxtoan_tools.statistics_manager"); 
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
                $historyManagerDefinition = $container->findDefinition("maxtoan_tools.history_manager");
                $historyManagerDefinition->addArgument($adapterDefinition);                
            }
        }

        // Manejador de documentos
        if ($container->getParameter('maxtoan_tools.object_manager.document.enable') === true) {
            $adapterDefinition = $container->findDefinition($config["document_manager"]["adapter"]);
            $documentManager = $container->findDefinition("maxtoan_tools.document_manager");
            $documentManager->addArgument($adapterDefinition);

            // Manejador de exportaciones
            $adapterDefinition = $container->findDefinition($config["exporter_manager"]["adapter"]);
            $exporterManager = $container->getDefinition("maxtoan_tools.exporter_manager");
            $exporterManager->addArgument($documentManager);
            $exporterManager->addMethodCall("setAdapter", array($adapterDefinition));
            $chaines = $container->findTaggedServiceIds("exporter.chain");
            $models = $container->findTaggedServiceIds("exporter.chain.model");
            foreach ($models as $id => $model) {
                $idChain = $model[0]["chain"];
                if (!isset($chaines[$idChain])) {
                    throw new \InvalidArgumentException(sprintf("The exporter chain '%s' is not exists.", $idChain));
                }
                $chain = $container->getDefinition($idChain);
                $chain->addMethodCall("add", [$container->getDefinition($id)]);
            }
            foreach ($chaines as $id => $chain) {
                $exporterManager->addMethodCall("addChainModel", [$container->getDefinition($id)]);
            }
        }
    }
}
