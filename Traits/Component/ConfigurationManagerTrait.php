<?php

/*
 * This file is part of the Maximosojo Tools package.
 * 
 * (c) https://maximosojo.github.io/tools-bundle
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maximosojo\ToolsBundle\Traits\Component;

/**
 * ConfigurationManagerTrait
 * 
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
trait ConfigurationManagerTrait
{    
    /**
     * Manejador de configuraciones
     *  
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @return ConfigurationManager
     */
    public function getConfigurationManager()
    {
        return $this->container->get("maximosojo_tools.manager.configuration");
    }
}