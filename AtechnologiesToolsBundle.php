<?php

/*
 * This file is part of the Atechnologies package.
 * 
 * (c) www.atechnologies.com.ve
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Atechnologies\ToolsBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * AtechnologiestoolsBundle
 * 
 * @author Máximo Sojo maxsojo13@gmail.com <maxtoan at atechnologies>
 */
class AtechnologiesToolsBundle extends Bundle
{
	public function build(\Symfony\Component\DependencyInjection\ContainerBuilder $container) 
    {
        parent::build($container);
        //Agrega repositorios como servicios e inyecta contenedor de dependencias
        $container->addCompilerPass(new DependencyInjection\Compiler\LinkGeneratorPass());
    }
}