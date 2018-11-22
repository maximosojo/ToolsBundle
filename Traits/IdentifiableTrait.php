<?php

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
