<?php

/*
 * This file is part of the Maximosojo Tools package.
 * 
 * (c) https://maximosojo.github.io/tools-bundle
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maximosojo\ToolsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as BaseAbstractController;
use Maximosojo\ToolsBundle\DependencyInjection\DoctrineTrait;
use Maximosojo\ToolsBundle\Traits\Component\EventDispatcherTrait;

/**
 * Controlador base
 *
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
class AbstractController extends BaseAbstractController
{
    use ControllerTrait;
    use DoctrineTrait;
    use EventDispatcherTrait;

    /**
     * Tipo error
     */
    public const TYPE_DANGER = "error";
    
    /**
     * Tipo éxito
     */
    public const TYPE_SUCCESS = "success";
    
    /**
     * Tipo alerta
     */
    public const TYPE_WARNING = "warning";
    
    /**
     * Tipo información
     */
    public const TYPE_INFO = "info";

    /**
     * Tipo depuración
     */
    public const TYPE_DEBUG = "debug";

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
     * @param \Maximosojo\ToolsBundle\Controller\Request $request
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
     * Returns true if the request is a JsonHttpRequest.
     * @return boolean
     */
    protected function isJsonHttpRequest()
    {
        $request = $this->container->get('request_stack')->getCurrentRequest();
        return 'json' == $request->getRequestFormat();
    }
}