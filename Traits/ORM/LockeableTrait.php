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
 * LockeableTrait
 *
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
trait LockeableTrait 
{   
    /**
     * ¿EL objeto está habilitado para consultas?
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    protected $locked = false;
    
    /**
     * Is locked?
     * @return boolean
     */
    public function isLocked() 
    {
        return $this->locked;
    }
    
    /**
     * Get locked
     *
     * @return boolean
     */
    public function getLocked()
    {
        return $this->locked;
    }

    /**
     * Set locked
     * @param boolean $locked
     * @return $this
     */
    public function setLocked($locked): self
    {
        $this->locked = (boolean)$locked;
        
        return $this;
    }
}