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
    }
}
