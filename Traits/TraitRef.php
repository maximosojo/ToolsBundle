<?php

/*
 * This file is part of the GTI SOLUTIONS, C.A. - J409603954 package.
 * 
 * (c) www.gtisolutions.com.ve
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Atechnologies\ToolsBundle\Traits;

use Doctrine\ORM\Mapping as ORM;

/**
 * Campos de referencia
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
trait TraitRef 
{
    /**
     * Referencia de la reseva
     * @var string 
     * @ORM\Column(name="ref",type="string",nullable=false,length=30,unique=false)
     */
    protected $ref;
    
    function getRef() 
    {
        return $this->ref;
    }

    function setRef($ref) 
    {
        $this->ref = $ref;
        
        return $this;
    }
}
