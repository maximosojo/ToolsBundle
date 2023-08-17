<?php

namespace Maximosojo\ToolsBundle\Service\Notifier\Texter\Adapter;

/**
 * TexterAdapterInterface
 *
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
interface TexterAdapterInterface
{    
    /*
     * Guarda los cambios en la base de datos
     */
    public function flush();
    
    public function persist($entity);
    
    public function remove($entity);
    
    /**
     * createSmsQueue
     */
    public function createSmsQueue();
}
