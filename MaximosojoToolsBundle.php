<?php

/*
 * This file is part of the Maximosojo Tools package.
 * 
 * (c) https://maximosojo.github.io/tools-bundle
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maximosojo\ToolsBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

// Directories and Paths
define('DS', '/');
if (!defined('TOOLS_ROOT')) {
    define('TOOLS_ROOT', str_replace(DIRECTORY_SEPARATOR, DS, getcwd()));
}
define('ROOT_DIR', TOOLS_ROOT . '/');

/**
 * MaximosojoToolsBundle
 * 
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
class MaximosojoToolsBundle extends Bundle
{
	public function build(\Symfony\Component\DependencyInjection\ContainerBuilder $container) 
    {
        parent::build($container);
        $container->addCompilerPass(new DependencyInjection\Compiler\LinkGeneratorPass());
        $container->addCompilerPass(new DependencyInjection\Compiler\ConfigurationPass());
    }
}