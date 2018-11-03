<?php

/*
 * This file is part of the Atechnologies package.
 * 
 * (c) www.atechnologies.com.ve
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Atechnologies\ToolsBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route as RouteSensio;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;

/**
 * Controlador base
 *
 * @author Máximo Sojo maxsojo13@gmail.com <maxtoan at atechnologies>
 */
class BaseController extends FOSRestController
{
    /**
     * Tipo error
     */
    const TYPE_DANGER = "error";
    
    /**
     * Tipo éxito
     */
    const TYPE_SUCCESS = "success";
    
    /**
     * Tipo alerta
     */
    const TYPE_WARNING = "warning";
    
    /**
     * Tipo información
     */
    const TYPE_INFO = "info";

    /**
     * Tipo depuración
     */
    const TYPE_DEBUG = "debug";

    /**
     * Bandera para permitir una transaccion simultanea
     * @var type 
     */
    private $isBeginTransaction = false;

    /**
     * $jsonResponse
     */
    private $jsonResponse;

	/**
     * Retorna el repositorio principal
     * @return \Atechnologies\ToolsBundle\Model\Base\EntityRepository
     */
    protected function getRepository($repository = null)
    {
        $em = $this->getDoctrine()->getManager();
        
        if (!$repository) {
            $repository = $this->getClass();
        }

        return $em->getRepository($repository);
    }
    
    /**
     * Debe retornar la clase principal que se esta manejando
     * @throws \Atechnologies\ToolsBundle\Exception\NotImplementedException
     */
    protected function getClass()
    {
        throw new \Exception("Error class not found", 1);
    }

    /**
     * Crea una nueva instancia
     * @throws type
     */
    protected function createNew($class = null)
    {
        if (!$class) {
            $class = $this->getClass();
        }

        return new $class();
    }
    
    /**
     * Busca un elemento o lanza una exepcion de 404
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return type
     * @throws type
     */
    protected function findOr404(\Symfony\Component\HttpFoundation\Request $request) 
    {
        $id = $request->get("id");
        if(empty($id)){
            throw $this->createNotFoundException("The identifier can not be empty.");
        }

        $resource = $this->getRepository()->find($id);
        if(!$resource){
            throw $this->createNotFoundException();
        }
        
        return $resource;
    }
    
    /**
     * Respuestas json mejoradas
     * @author Máximo Sojo maxsojo13@gmail.com <maxtoan at atechnologies>
     * @param  string  $message
     * @param  integer $code 
     * @return JsonResponse
     * @deprecated
     */
    public function jsonResponse($message, $code = 200) 
    {
        $result = [
            'result' => $message
        ];
        
        return new JsonResponse($result, $code);
    }

    /**
     * Traducción
     * @author Máximo Sojo maxsojo13@gmail.com <maxtoan at atechnologies>
     * @param  [type]
     * @param  array
     * @param  string
     * @return [type]
     */
    protected function trans($id,array $parameters = array(), $domain = 'app')
    {
        return $this->container->get('translator')->trans($id, $parameters, $domain);
    }

    /**
     * GenericEvent
     *  
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @param  Entity
     * @return GenericEvent
     */
    public function newGenericEvent($entity)
    {
        return new \Atechnologies\ToolsBundle\Model\EventDispatcher\GenericEvent($entity);        
    }

    /**
     * Disparar un evento
     * @param type $eventName
     * @param \Symfony\Component\EventDispatcher\Event $event
     * @return \Atechnologies\ToolsBundle\Event\GenericEvent
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
        return $this->get("event_dispatcher");
    }

    /**
     * Inicia una transaccion en la base de datos
     */
    protected function managerBeginTransaction()
    {
        if($this->isBeginTransaction === true){
            throw new \LogicException("No puede iniciar la transaccion dos veces. Realize el commit de la anterior");
        }

        $this->getDoctrine()->getManager()->getConnection()->beginTransaction();

        $this->isBeginTransaction = true;
    }

    /**
     * Realiza el commit de una transaccion
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @return Doctrine
     */
    protected function managerCommit()
    {
        if($this->isBeginTransaction === false){
            throw new \LogicException("No hay ninguna transaccion iniciada, primero debe iniciarla.");
        }

        $em = $this->getDoctrine()->getManager();        
        $em->flush();
        $em->getConnection()->commit();
        
        $this->isBeginTransaction = false;
    }

    /**
     * Roll back si falla la transaccion
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @return Doctrine
     */
    protected function managerRollback()
    {
        if($this->isBeginTransaction === false){
            //throw new \LogicException("No hay ninguna transaccion iniciada, primero debe iniciarla.");
            return;
        }
        $this->getDoctrine()->getManager()->getConnection()->rollback();
        $this->isBeginTransaction = false;
    }
    
    /**
     * Guarda entidad
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @param  Entity
     * @param  boolean
     * @return Entity
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
     * Remove object
     * @author Máximo Sojo maxsojo13@gmail.com <maxtoan at atechnologies>
     * @param  Entity
     * @param  boolean
     * @return Entity
     */
    protected function remove($entity = null, $andFlush = true) 
    {
        $em = $this->getDoctrine()->getManager();
        try {
            if ($entity !== null) {
                $em->remove($entity);
            }
            if ($andFlush === true) {
                $em->flush();
            }            
        } catch (Exception $e) {
            $em->rollBack();
        }
    }
    
    /**
     * Flush  object
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
     * Añade los campos a expandir a una vista
     * @param \FOS\RestBundle\View\View $view
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param array $defaults
     */
    protected function addExpandFields(\FOS\RestBundle\View\View $view,\Symfony\Component\HttpFoundation\Request $request,array $defaults = array()) 
    {
        $view->getContext()->setGroups($this->buildExpandFields($request,$defaults));
    }
    
    /**
     * Construye el una variable para expandir campos dinamicamente al serializar
     * @param \Atechnologies\ToolsBundle\Controller\Request $request
     * @param array $defaults
     * @return type
     */
    protected function buildExpandFields(\Symfony\Component\HttpFoundation\Request $request,$defaults = array()) 
    {
        $expandString = $request->get("expand",null);
        if(is_string($defaults) && !empty($defaults)){
            $expandString .= $defaults;
        }
        if(!is_array($defaults)){
            $defaults = [];
        }
        $expandArray = [];
        if($expandString !== null){
            $expandArray = explode(",",  str_replace(" ","",$expandString));
            foreach ($expandArray as $key => $value) {
                if($value == ""){
                    unset($expandArray[$key]);
                }elseif(in_array($value,$defaults)){
                    unset($expandArray[$key]);
                }
            }
        }
        $toExpand = array_merge($expandArray,$defaults);
        if(count($toExpand) == 0){
            $toExpand[] = "no_empty";
        }

        return $toExpand;
    }

    /**
     * Json reponse
     * @param type $data
     * @param type $status
     * @param type $headers
     * @return \Atechnologies\ToolsBundle\Custom\HttpFoundation\MyJsonResponse
     */
    protected function myJsonResponse($data = null, $status = 200, $headers = array()) 
    {
        if (!$this->jsonResponse) {
            $this->jsonResponse = new \Atechnologies\ToolsBundle\Custom\HttpFoundation\MyJsonResponse($data,$status,$headers);
        }

        return $this->jsonResponse;
    }

    /**
     * Set flash in json reponse
     * @author Máximo Sojo <maxsojo13@gmail.com>
     */
    public function setFlashJson($typeFlash, $value, $parameters = array(), $status = 200)
    {
        $response = $this->myJsonResponse(null,$status);
        $response->setFlash($typeFlash, $value, $parameters);
        return $response;
    }

    /**
     * Json redirect
     * @author Máximo Sojo maxsojo13@gmail.com <maxtoan at atechnologies>
     * @param  [type]
     * @return [type]
     */
    public function setJsonRedirect($url)
    {
        $response = $this->myJsonResponse();
        $response->setRedirect($url);
        return $response;
    }
}