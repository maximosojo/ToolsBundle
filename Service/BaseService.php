<?php

/*
 * This file is part of the Atechnologies package.
 * 
 * (c) www.atechnologies.com.ve
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Atechnologies\ToolsBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

/**
 * Servicio base con implementación de funciones genericas compartidas
 * service (atechnologies.service.base)
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
class BaseService implements ContainerAwareInterface 
{
    use \Atechnologies\ToolsBundle\DependencyInjection\ContainerAwareTrait;
    use \Atechnologies\ToolsBundle\DependencyInjection\DoctrineTrait;

    /**
     * Base de archivos de comandos de impresoras
     * @author Máximo Sojo maxsojo13@gmail.com <maxtoan at atechnologies>
     * @return Base url
     */
    public function getBaseUrl()
    {
        return $this->getContainer()->getParameter('kernel.root_dir');
    }

    /**
     * Consulta de container
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Get a user from the Security Context
     * @return mixed
     * @throws LogicException If SecurityBundle is not available
     * @see TokenInterface::getUser()
     */
    public function getUser()
    {
        if (!$this->container->has('security.token_storage')) {
            throw new LogicException('The SecurityBundle is not registered in your application.');
        }

        if (null === $token = $this->container->get('security.token_storage')->getToken()) {
            return;
        }

        if (!is_object($user = $token->getUser())) {
            return;
        }

        return $user;
    }
    
    /**
     * Traducciones
     * @author Máximo Sojo maxsojo13@gmail.com <maxtoan at atechnologies>
     * @param  [type]
     * @param  array
     * @param  string
     * @return [type]
     */
    protected function trans($id, array $parameters = array(), $domain = 'messages') 
    {
        return $this->container->get('translator')->trans($id, $parameters, $domain);
    }
    
     /**
     * Disparar un evento
     * @param type $eventName
     * @param \Symfony\Component\EventDispatcher\Event $event
     * @return \Atechnologies\ToolsBundle\Service\Event\GenericEvent
     */
    protected function dispatch($eventName, \Symfony\Component\EventDispatcher\Event $event = null)
    {
        return $this->getEventDispatcher()->dispatch($eventName, $event);
    }
    
    /**
     * Retorna el disparador de eventos
     * @return \Symfony\Component\EventDispatcher\EventDispatcher
     */
    protected function getEventDispatcher()
    {
        return $this->getContainer()->get("event_dispatcher");
    }    
}
