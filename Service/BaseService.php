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
 * @author Máximo Sojo maxsojo13@gmail.com <maxtoan at atechnologies>
 */
class BaseService implements ContainerAwareInterface 
{
    use \Atechnologies\ToolsBundle\DependencyInjection\ContainerAwareTrait;
    
    /**
     * Base de archivos de comandos de impresoras
     * @author Máximo Sojo maxsojo13@gmail.com <maxtoan at atechnologies>
     * @return [type]
     */
    public function getBaseUrl()
    {
        return $this->getContainer()->getParameter('kernel.root_dir');
    }

    /**
     * Retorna el repositorio
     * @return \Atechnologies\ToolsBundle\Service\EntityRepository
     */
    protected function getRepository($class)
    {
        if(!$class){
            throw new \Exception("Error class not found", 1);            
        }
        $em = $this->getDoctrine()->getManager();
        return $em->getRepository($class);
    }

    /**
     * Guarda una entidad
     * @param type $entity
     * @param type $andFlush
     */
    protected function save($entity, $andFlush = true)
    {
        $em = $this->getDoctrine()->getManager();
        try {
            $em->persist($entity);
            if($andFlush === true){
                $em->flush();
            }
        } catch (Exception $e) {
            $em->rollBack();
        }
    }

    /**
     * Flush  de objeto
     * @author Máximo Sojo maxsojo13@gmail.com <maxtoan at atechnologies>
     * @return [type]
     */
    protected function flush()
    {
        $em = $this->getDoctrine()->getManager();
        try {
            $em->flush();            
        } catch (Exception $e) {
            $em->rollBack();
        }
    }
    
    /**
     * Shortcut to return the Doctrine Registry service.
     * @return Registry
     * @throws LogicException If DoctrineBundle is not available
     */
    public function getDoctrine() 
    {
        if (!$this->container->has('doctrine')) {
            throw new LogicException('The DoctrineBundle is not registered in your application.');
        }

        return $this->container->get('doctrine');
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
     * Consulta de container
     * @author Máximo Sojo maxsojo13@gmail.com <maxtoan at atechnologies>
     * @return [type]
     */
    public function getContainer()
    {
        return $this->container;
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
