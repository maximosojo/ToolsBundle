<?php

/*
 * This file is part of the Máximo Sojo - maximosojo package.
 * 
 * (c) https://maximosojo.github.io/
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maximosojo\ToolsBundle\Traits;

use Doctrine\ORM\Mapping as ORM;

/**
 * IdentifiableTrait
 *
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
trait IdentifiableTrait 
{   
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;
    
    /**
     * Get Id
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}
