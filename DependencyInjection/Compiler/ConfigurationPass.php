<?php

/*
 * This file is part of the Maximosojo Tools package.
 * 
 * (c) https://maximosojo.github.io/tools-bundle
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maximosojo\ToolsBundle\DependencyInjection\Compiler;

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
        // Registra manejador de objetos
        $config = $container->getParameter("maximosojo_tools.object_manager.options");

        // Manejador de estadísticas
        if ($container->getParameter('maximosojo_tools.object_manager.statistic.enabled') === true) {
            $statistic = $container->getParameter("maximosojo_tools.object_manager.statistic");
            /*$idStatisticManagerAdapter = $statistic["adapter"];
            if ($idStatisticManagerAdapter && $container->hasDefinition($idStatisticManagerAdapter)) {
                $adapterDefinition = $container->findDefinition($idStatisticManagerAdapter);
                $statisticManagerDefinition = $container->findDefinition("maximosojo_tools.statistics_manager.default");
                $statisticManagerDefinition->addArgument($adapterDefinition);                
            }*/

            $statisticManager = $container->getDefinition("maximosojo_tools.statistics_manager.default"); 
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
        if ($container->getParameter('maximosojo_tools.object_manager.history.enabled') === true) {
            $history = $container->getParameter("maximosojo_tools.object_manager.history");
            $idHistoryManagerAdapter = $history["adapter"];
            if ($idHistoryManagerAdapter && $container->hasDefinition($idHistoryManagerAdapter)) {
                $adapterDefinition = $container->findDefinition($idHistoryManagerAdapter);
                $historyManagerDefinition = $container->findDefinition("maximosojo_tools.history_manager.default");
                $historyManagerDefinition->addArgument($adapterDefinition);
            }
        }

        // Manejador de documentos
        if ($container->getParameter('maximosojo_tools.object_manager.document.enabled') === true) {
            // Manejador de documentos
            $adapterDefinition = $container->findDefinition($config["document_manager"]["adapter"]);
            $documentManager = $container->findDefinition("maximosojo_tools.document_manager.default");
            $documentManager->addArgument($adapterDefinition);
        }

        // Manejador de exportaciones
        if ($container->getParameter('maximosojo_tools.object_manager.exporter.enabled') === true) {
            $adapterDefinition = $container->findDefinition($config["exporter_manager"]["adapter"]);
            $exporterManager = $container->getDefinition("maximosojo_tools.exporter_manager.default");
            $exporterManager->addArgument($documentManager);
            $exporterManager->addArgument($config["exporter_manager"]);
            $exporterManager->addMethodCall("setAdapter", array($adapterDefinition));
            // Registro de chaines soportados
            $chaines = $config["exporter_manager"]["chaines"];
            foreach ($chaines as $key => $chain) {
                $exporterManager->addMethodCall("addChainModel", [$chain]);
            }

            // Registra adaptador en el servicio de plantillas
            $templateService = $container->getDefinition("maximosojo_tools.template_service");
            $adapters = $container->findTaggedServiceIds("exporter.template.adapter");
            foreach ($adapters as $id => $adapter) {
                $templateService->addMethodCall("setAdapter", [$container->getDefinition($id)]);
            }
        }

        // Manejador de mensajes
        if ($container->getParameter('maximosojo_tools.notifier.texter.enabled') === true) {
            $transports = $container->getParameter('maximosojo_tools.notifier.texter.transports');
            // Manejador de notificaciones texter
            $texterManager = $container->getDefinition("maximosojo_tools.notifier.texter_manager");
            // Inicia opciones
            $container->setParameter("maximosojo_tools.notifier.texter_manager.transports.twilio.options", []);
            // Interconectados
            if ($transports["interconectados"]["enabled"] === true) {
                // DSN
                $dsn = explode(":",$transports["interconectados"]["dsn"]);
                $options = [
                    "enabled" => true,
                    "user" => $dsn[0],
                    "password" => $dsn[1]
                ];
                
                $interconectadosTransport = $container->getDefinition("maximosojo_tools.notifier.texter_manager.transports.interconectados");
                $interconectadosTransport->setOptions($options);
                // Transport
                $texterManager->addMethodCall("addTransport", [$interconectadosTransport]);
            }

            // Twilio
            if ($container->getParameter("maximosojo_tools.notifier.texter.transports.twilio.enabled") === true) {
                // DSN
                $dsn = explode(":",$_ENV["MAXIMOSOJO_TOOLS_NOTIFIER_TEXTER_TWILIO_DSN"]);
                $options = [
                    "enabled" => true,
                    "sid" => $dsn[0],
                    "token" => $dsn[1],
                    "number" => $dsn[2]
                ];

                $twilioTransport = $container->getDefinition("maximosojo_tools.notifier.texter_manager.transports.twilio");
                $twilioTransport->setOptions($options);
                // Transport
                $texterManager->addMethodCall("addTransport", [$twilioTransport]);
            }

            // Dummy
            if ($container->getParameter("maximosojo_tools.notifier.texter.transports.dummy.enabled") === true) {
                // Transport
                $texterManager->addMethodCall("addTransport", [$container->getDefinition("maximosojo_tools.notifier.texter_manager.transports.dummy")]);
            }
        }

        // Manejador de opciones
        if ($container->getParameter('maximosojo_tools.option_manager.enabled') === true) {
            $config = $container->getParameter("maximosojo_tools.option_manager.options");
            $optionManager = $container->getDefinition("maximosojo_tools.option_manager.default");
            $tags = $container->findTaggedServiceIds('option_manager.wrapper');
            foreach ($tags as $id => $params) {
                $definition = $container->findDefinition($id);
                $optionManager->addMethodCall("addWrapper", array($definition));
            }
        }
    }
}
