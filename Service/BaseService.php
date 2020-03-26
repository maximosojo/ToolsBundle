<?php

/*
 * This file is part of the Maxtoan Tools package.
 * 
 * (c) https://maximosojo.github.io/tools-bundle
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maxtoan\ToolsBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Servicio base con implementación de funciones genericas compartidas
 * service (maxtoan_tools.service.base)
 * 
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
class BaseService implements ContainerAwareInterface 
{
    use \Maxtoan\ToolsBundle\DependencyInjection\ContainerAwareTrait;
    use \Maxtoan\ToolsBundle\DependencyInjection\DoctrineTrait;

    /**
     * Shortcut to return the Doctrine Registry service.
     * @return Registry
     * @throws LogicException If DoctrineBundle is not available
     */
    public function getDoctrine() 
    {
        if (!$this->getContainer()->has('doctrine')) {
            throw new LogicException('The DoctrineBundle is not registered in your application.');
        }

        return $this->getContainer()->get('doctrine');
    }
    
    /**
     * Base de archivos de comandos de impresoras
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @return Base url
     */
    public function getBaseUrl()
    {
        return $this->getContainer()->getParameter('kernel.root_dir');
    }

    /**
     * Get a user from the Security Context
     * @return mixed
     * @throws LogicException If SecurityBundle is not available
     * @see TokenInterface::getUser()
     */
    public function getUser()
    {
        if (!$this->getContainer()->has('security.token_storage')) {
            throw new LogicException('The SecurityBundle is not registered in your application.');
        }

        if (null === $token = $this->getContainer()->get('security.token_storage')->getToken()) {
            return;
        }

        if (!is_object($user = $token->getUser())) {
            return;
        }

        return $user;
    }
    
    /**
     * Traducciones
     * @author Máximo Sojo <maxsojo13@gmail.com>
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
     * @return \Maxtoan\ToolsBundle\Service\Event\GenericEvent
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

    /**
     * Genera una url
     * @param type $route
     * @param array $parameters
     * @return type
     */
    protected function generateUrl($route, $parameters = array(), $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH)
    {
        return $this->container->get('router')->generate($route, $parameters, $referenceType);
    }

    /**
     * Container
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @return Container
     */
    public function getContainer()
    {
        return $this->container;
    }
}
