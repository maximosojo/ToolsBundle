<?php

/*
 * This file is part of the Maximosojo Tools package.
 * 
 * (c) https://maximosojo.github.io/tools-bundle
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maximosojo\ToolsBundle\Traits\ORM\Basic;

use Doctrine\ORM\Mapping as ORM;

/**
 * Data extra de una entidad
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
trait ExtraDataTrait
{
    /**
     * Informacion extra
     * @var string
     * @ORM\Column(name="extra_data",type="json")
     */
    protected $extraData;
    
    /**
     * setExtraData
     *
     * @param   String $key
     * @param   String $value
     *
     * @return  ExtraData
     */
    public function setExtraData($key,$value)
    {
        if(!is_array($this->extraData)){
            $this->extraData = [];
        }
        $this->extraData[$key] = $value;
        
        return $this;
    }

    /**
     * setExtras
     *
     * @param   array  $extra
     *
     * @return  ExtraData
     */
    public function setExtras(array $extra)
    {
        $this->extraData = $extra;

        return $this;
    }
    
    /**
     * getExtraData
     *
     * @param   String  $key
     * @param   String  $default
     *
     * @return  String
     */
    public function getExtraData($key,$default = null)
    {
        if(isset($this->extraData[$key])){
            $default = $this->extraData[$key];
        }
        
        return $default;
    }
}
