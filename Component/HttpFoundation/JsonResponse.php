<?php

/*
 * This file is part of the Maximosojo Tools package.
 * 
 * (c) https://maximosojo.github.io/tools-bundle
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maximosojo\ToolsBundle\Component\HttpFoundation;

use Symfony\Component\HttpFoundation\JsonResponse as JsonResponseBase;

/**
 * JsonResponse custom
 * 
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
class JsonResponse extends JsonResponseBase 
{   
    /**
     * $originalData
     * @var string
     */
    protected $originalData;

    /**
     * $extraData
     * @var string
     */
    protected $extraData;
    
    public function __construct($data = null, $status = 200, $headers = array()) 
    {
        $this->extraData = [];
        parent::__construct($data, $status, $headers);
    }
    
    /**
     * Carga de data
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @param  array
     */
    public function setData(mixed $data = array()): static
    {
        $this->originalData = $data;
        if(count($this->extraData) > 0){
            $data["_server"] = $this->extraData;
        }

        return parent::setData($data);
    }
    
    /**
     * Mensaje flash
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @param  string
     * @param  string
     */
    public function setFlash($type, $message) 
    {
        if(!isset($this->extraData["flashes"])){
            $this->extraData["flashes"] = [];
        }

        $this->extraData["flashes"][] = [
            "type" => $type,
            "message" => $message,
        ];

        $this->setData($this->originalData);
    }
    
    /**
     * Redirección
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @param  string
     */
    public function setRedirect($url) 
    {
        $this->extraData["redirect"] = $url;
        $this->setData($this->originalData);
    }

    /**
     * Redirección
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @param  string
     */
    public function setForceRedirect($url) 
    {
        $this->extraData["force_redirect"] = $url;
        $this->setData($this->originalData);
    }

    /**
     * Reload json
     * @author Máximo Sojo <maxsojo13@gmail.com>
     */
    public function setForceReload() 
    {
        $this->extraData["force_reload"] = true;
        $this->setData($this->originalData);
    }

    /**
     * Refresca paginador
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @deprecated
     */
    public function setRefreshPaginator($key = true) 
    {
        $this->extraData["refresh_paginator"] = $key;
        $this->setData($this->originalData);
    }

    /**
     * Refresca según la llave
     * @author Máximo Sojo <maxsojo13@gmail.com>
     */
    public function setRefresh($key = true) 
    {
        $this->extraData["refresh"] = $key;
        $this->setData($this->originalData);
    }

    /**
     * Abrir popUP
     *  
     * @param  string $url
     * @param  array  $parameters
     */
    public function setOpenPopUp($url,array $parameters = []) 
    {
        $this->extraData["openPopUp"] = [
            "url" => $url,
            "parameters" => $parameters,
        ];
        $this->setData($this->originalData);
    }

    /**
     * Recarga el contenido sin refrescar la pagina completa
     *
     * @return  JsonResponse
     */ 
    public function setReloadContent() 
    {
        $this->extraData["reload_content"] = true;
        $this->setData($this->originalData);
    }

    /**
     * Redirección a pagina anterior
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @param  string
     */
    public function setBackRedirect() 
    {
        $this->extraData["back_redirect"] = true;
        $this->setData($this->originalData);
    }
}