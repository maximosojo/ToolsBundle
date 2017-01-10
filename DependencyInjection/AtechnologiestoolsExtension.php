<?php

namespace Atechnologies\ToolsBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;

class AtechnologiestoolsExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
    	$loaderYml = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loaderYml->load('services.yml');

        $loaderXml = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loaderXml->load('services.xml');
    }
}