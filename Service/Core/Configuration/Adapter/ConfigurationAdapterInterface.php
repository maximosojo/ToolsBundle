<?php

/*
 * This file is part of the BtoB4Rewards package.
 * 
 * (c) www.btob4rewards.com
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maximosojo\ToolsBundle\Service\Core\Configuration\Adapter;

use Maximosojo\ToolsBundle\Model\Core\Configuration\ConfigurationInterface;

/**
 * Adaptador de las configuraciones
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
interface ConfigurationAdapterInterface
{
    public function find($key);
    public function findAll();
    
    /*
     * Guarda los cambios en la base de datos
     */
    public function flush();
    
    /**
     * Guarda los cambios de la configuracion
     * @param ConfigurationInterface $configuration
     */
    public function persist(ConfigurationInterface $configuration);
    
    /**
     * @return \Maximosojo\ToolsBundle\Model\Core\Configuration\ConfigurationInterface Description
     */
    public function createNew();
}
