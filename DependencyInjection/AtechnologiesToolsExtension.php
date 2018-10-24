<?php

/*
 * This file is part of the Atechnologies package.
 * 
 * (c) www.atechnologies.com.ve
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Atechnologies\ToolsBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Definition;

/**
 * AtechnologiestoolsExtension
 * @author Máximo Sojo maxsojo13@gmail.com <maxtoan at atechnologies>
 */
class AtechnologiesToolsExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {	
        $processor = new Processor();
        $configuration = new Configuration();
        
        $config = $processor->processConfiguration($configuration, $configs);
        if (isset($config['paginator']['format_array'])) {
            $container->setParameter('paginator_format_array', $config['paginator']['format_array']);
        }
        
        $loaderYml = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loaderYml->load('services.yml');
    }
}