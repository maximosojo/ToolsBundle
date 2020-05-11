<?php

/*
 * This file is part of the Maxtoan Tools package.
 * 
 * (c) https://maximosojo.github.io/tools-bundle
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maxtoan\ToolsBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route as RouteSensio;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Contracts\Translation\TranslatorInterface;
use Maxtoan\ToolsBundle\DependencyInjection\DoctrineTrait;
use Maxtoan\ToolsBundle\Traits\Component\EventDispatcherTrait;

/**
 * Controlador base
 *
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
class BaseController extends AbstractFOSRestController
{
    use ControllerTrait;
    use DoctrineTrait;
    use EventDispatcherTrait;

    /**
     * $translator
     * @var Translator
     */
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

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
     * Respuestas json mejoradas
     * @author Máximo Sojo <maxsojo13@gmail.com>
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
     * @param \Maxtoan\ToolsBundle\Controller\Request $request
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

    /**
     * Traducción
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @param  String
     * @param  array
     * @param  string
     * @return Translation
     */
    protected function trans($id,array $parameters = array(), $domain = "")
    {
        return $this->translator->trans($id, $parameters, $domain);
    }   
}