<?php

/*
 * This file is part of the Maximosojo Tools package.
 * 
 * (c) https://maximosojo.github.io/tools-bundle
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maximosojo\ToolsBundle\Traits\ORM;

use Doctrine\ORM\Mapping as ORM;

/**
 * EnableableTrait
 *
 * @author Matías Jiménez <matei249@gmail.com>
 * @author Máximo Sojo <maxsojo13@gmail.com>
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
    public function setEnabled($enabled): self
    {
        $this->enabled = (boolean)$enabled;
        
        return $this;
    }
}