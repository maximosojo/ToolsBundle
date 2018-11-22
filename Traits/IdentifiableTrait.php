<?php

/*
 * This file is part of the Máximo Sojo - maxtoan package.
 * 
 * (c) https://maxtoan.github.io/
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Atechnologies\ToolsBundle\Traits;

use Doctrine\ORM\Mapping as ORM;

/**
 * IdentifiableTrait
 *
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
trait IdentifiableTrait 
{   
    /**
     * @ORM\Column(name="id", type="string", length=36)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
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
