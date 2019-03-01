<?php

/*
 * This file is part of the GTI SOLUTIONS, C.A. - J409603954 package.
 * 
 * (c) www.gtisolutions.com.ve
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maxtoan\ToolsBundle\Component\EventDispatcher;

use Symfony\Component\EventDispatcher\GenericEvent as GenericEventBase;

/**
 * Base generica de eventos
 * 
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class GenericEvent extends GenericEventBase
{
	/**
     * @var Entity
     */
    protected $entity;
 	
 	/**
     * @var \Symfony\Component\HttpFoundation\Response
     */
    protected $response;
     
    /**
     * $message
     * @var null
     */
    private $message = null;
             
    public function __construct($entity,array $arguments = array())
    {
        $this->entity = $entity;
        parent::__construct($entity, $arguments);
    }

    /**
     * Obtener entidad relacionada
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @return Entity
     */
    public function getEntity() 
    {
        return $this->entity;
    }
    
    /**
     * Obtener respuesta
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @return Response
     */
    public function getResponse() 
    {
        return $this->response;
    }

    /**
     * Registro de respuesta
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @param  \Symfony\Component\HttpFoundation\Response
     */
    public function setResponse(\Symfony\Component\HttpFoundation\Response $response) 
    {
         $this->response = $response;
    }

    /**
     * Retorno de mensaje
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @return message
     */
    public function getMessage() 
    {
        return $this->message;
    }

    /**
     * Registro de mensaje de respuesta
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @param  [type]
     */
    public function setMessage($message) 
    {
        $this->message = $message;
    }
    
    /**
     * Obtener parametros
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @param  String
     * @return Parameters
     */
    public function getParameter($key,$default = null) 
    {
        if($this->hasArgument($key)){
            return $this->arguments[$key];
        }
        
        return $default;
    }
}
