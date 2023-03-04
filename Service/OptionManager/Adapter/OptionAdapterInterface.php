<?php

/*
 * This file is part of the Maximosojo Tools package.
 * 
 * (c) https://maximosojo.github.io/tools-bundle
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maximosojo\ToolsBundle\Service\OptionManager\Adapter;

use Maximosojo\ToolsBundle\Service\OptionManager\OptionInterface;

/**
 * Adaptador de opciones
 * 
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
interface OptionAdapterInterface
{
    public function find($key);
    public function findAll();
    
    /*
     * Guarda los cambios en la base de datos
     */
    public function flush();
    
    /**
     * Guarda los cambios de la configuracion
     * @param OptionInterface $configuration
     */
    public function persist(OptionInterface $configuration);
    
    /**
     * @return \Maximosojo\ToolsBundle\Service\OptionManager\OptionInterface
     */
    public function createNew();
}
