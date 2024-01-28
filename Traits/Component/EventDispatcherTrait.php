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

use Maximosojo\ToolsBundle\Component\EventDispatcher\GenericEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * EventDispatcherTrait
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
trait EventDispatcherTrait 
{
    protected $dispatcher;
    
    /**
     * GenericEvent
     *  
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @param  object
     * @return GenericEvent
     */
    public function newGenericEvent($entity)
    {
        return new GenericEvent($entity);        
    }
    
    /**
     * Disparar un evento
     * @param string $eventName
     * @param GenericEvent $event
     * @return \App\Event\GenericEvent
     */
    protected function dispatch(string $eventName, GenericEvent $event = null)
    {
        return $this->dispatcher->dispatch($event,$eventName);
    }

    /**
     * Retorna el disparador de eventos
     * @return \Symfony\Component\EventDispatcher\EventDispatcher
     */
    protected function getEventDispatcher()
    {
        return $this->dispatcher;
    }

    /**
     * setEventDispatcher
     *  
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @param  EventDispatcherInterface $dispatcher
     * @return EventDispatcherInterface
     * @required
     */
    public function setEventDispatcher(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }
}