<?php

/*
 * This file is part of the Atechnologies package.
 * 
 * (c) www.atechnologies.com.ve
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Atechnologies\ToolsBundle\Custom\HttpFoundation;

use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * MyJsonResponse custom
 * 
 * @author Máximo Sojo maxsojo13@gmail.com <maxtoan at atechnologies>
 */
class MyJsonResponse extends JsonResponse 
{   
    /**
     * $originalData
     * @var String
     */
    protected $originalData;

    /**
     * $extraData
     * @var String
     */
    protected $extraData;
    
    public function __construct($data = null, $status = 200, $headers = array()) 
    {
        $this->extraData = [];
        parent::__construct($data, $status, $headers);
    }
    
    /**
     * Carga de data
     * @author Máximo Sojo maxsojo13@gmail.com <maxtoan at atechnologies>
     * @param  array
     */
    public function setData($data = array()) 
    {
        $this->originalData = $data;
        if(count($this->extraData) > 0){
            $data["_server"] = $this->extraData;
        }

        return parent::setData($data);
    }
    
    /**
     * Mensaje flash
     * @author Máximo Sojo maxsojo13@gmail.com <maxtoan at atechnologies>
     * @param  String
     * @param  String
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
     * @author Máximo Sojo maxsojo13@gmail.com <maxtoan at atechnologies>
     * @param  String
     */
    public function setRedirect($url) 
    {
        $this->extraData["redirect"] = $url;
        $this->setData($this->originalData);
    }

    /**
     * Reload json
     * @author Máximo Sojo maxsojo13@gmail.com <maxtoan at atechnologies>
     */
    public function setForceReload() 
    {
        $this->extraData["forceReload"] = true;
        $this->setData($this->originalData);
    }
    
    /**
     * Abrir popup
     * @author Máximo Sojo maxsojo13@gmail.com <maxtoan at atechnologies>
     * @param  String
     * @param  array
     */
    public function setOpenPopUp($url,array $parameters = []) 
    {
        $this->extraData["openPopUp"] = [
            "url" => $url,
            "parameters" => $parameters,
        ];
        
        $this->setData($this->originalData);
    }
}