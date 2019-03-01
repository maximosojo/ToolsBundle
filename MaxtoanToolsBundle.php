<?php

/*
 * This file is part of the Maxtoan package.
 * 
 * (c) https://maxtoan.github.io/tools-bundle
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maxtoan\ToolsBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * MaxtoanToolsBundle
 * 
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
class MaxtoanToolsBundle extends Bundle
{
	public function build(\Symfony\Component\DependencyInjection\ContainerBuilder $container) 
    {
        parent::build($container);
        //Agrega repositorios como servicios e inyecta contenedor de dependencias
        $container->addCompilerPass(new DependencyInjection\Compiler\LinkGeneratorPass());
        $container->addCompilerPass(new DependencyInjection\Compiler\FactoryRepositoryPass());
    }
}