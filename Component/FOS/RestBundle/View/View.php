<?php

/*
 * This file is part of the Máximo Sojo - maxtoan package.
 * 
 * (c) https://maximosojo.github.io/
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maxtoan\ToolsBundle\Component\FOS\RestBundle\View;

use FOS\RestBundle\View\View as ViewBase;

/**
 * Description of View
 *
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
class View extends ViewBase
{   
    protected $originalData;
    protected $extraData;
    
    public function __construct($data = null, $statusCode = null, array $headers = array())
    {
        $this->extraData = [];
        parent::__construct($data, $statusCode, $headers);
    }
    
    public function setData($data = array())
    {
        $this->originalData = $data;
        if(count($this->extraData) > 0){
            $this->setHeader("_server",json_encode($this->extraData));
        }
        return parent::setData($data);
    }
    
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
}
