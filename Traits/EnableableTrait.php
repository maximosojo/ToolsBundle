<?php

/*
 * This file is part of the Atechnologies package.
 * 
 * (c) www.atechnologies.com.ve
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Atechnologies\ToolsBundle\Traits;

use Doctrine\ORM\Mapping as ORM;

/**
 * EnableableTrait
 *
 * @author Matías Jiménez matei249@gmail.com <matjimdi at atechnologies>
 * @author Máximo Sojo maxsojo13@gmail.com <maxtoan at atechnologies>
 */
trait EnableableTrait 
{   
    /**
     * ¿EL objeto está habilitado para consultas?
     * @var boolean
     * @ORM\Column(name="enabled",type="boolean")
     */
    protected $enabled = true;
    
    /**
     * Is Enabled?
     * @return boolean
     */
    public function isEnabled() 
    {
        return $this->enabled;
    }
    
    /**
     * Get enabled
     *
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set Enabled
     * @param boolean $enabled
     * @return $this
     */
    public function setEnabled($enabled)
    {
        $this->enabled = (boolean)$enabled;
        
        return $this;
    }
}