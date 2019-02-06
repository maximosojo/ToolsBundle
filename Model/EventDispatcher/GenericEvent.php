<?php

/*
 * This file is part of the GTI SOLUTIONS, C.A. - J409603954 package.
 * 
 * (c) www.gtisolutions.com.ve
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Atechnologies\ToolsBundle\Model\EventDispatcher;

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
             
    function __construct($entity,array $arguments = array())
    {
        $this->entity = $entity;
        parent::__construct($entity, $arguments);
    }

    function getEntity() 
    {
        return $this->entity;
    }
     
    function getResponse() 
    {
        return $this->response;
    }

    function setResponse(\Symfony\Component\HttpFoundation\Response $response) 
    {
         $this->response = $response;
    }

    function getMessage() 
    {
        return $this->message;
    }

    function setMessage($message) 
    {
        $this->message = $message;
    }
    
    public function getParameter($key,$default = null) 
    {
        if($this->hasArgument($key)){
            return $this->arguments[$key];
        }
        
        return $default;
    }
}
