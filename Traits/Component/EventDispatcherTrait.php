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
 * EventDispatcherTrait
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
trait EventDispatcherTrait 
{
    private $dispatcher;
    
    /**
     * GenericEvent
     *  
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @param  Entity
     * @return GenericEvent
     */
    public function newGenericEvent($entity)
    {
        return new \Maximosojo\ToolsBundle\Component\EventDispatcher\GenericEvent($entity);        
    }
    
    /**
     * Disparar un evento
     * @param type $eventName
     * @param \Maximosojo\ToolsBundle\Component\EventDispatcher\GenericEvent $event
     * @return \App\Event\GenericEvent
     */
    protected function dispatch($eventName, \Maximosojo\ToolsBundle\Component\EventDispatcher\GenericEvent $event = null)
    {
        return $this->getEventDispatcher()->dispatch($event,$eventName);
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
     * getEventDispatcher
     *  
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @param  \Symfony\Component\EventDispatcher\EventDispatcherInterface $dispatcher
     * @return EventDispatcherInterface
     * @required
     */
    public function setEventDispatcher(\Symfony\Component\EventDispatcher\EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }
}