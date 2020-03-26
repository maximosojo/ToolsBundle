<?php

/*
 * This file is part of the Maxtoan Tools package.
 * 
 * (c) https://maximosojo.github.io/tools-bundle
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maxtoan\ToolsBundle\Traits\Basic;

use Doctrine\ORM\Mapping as ORM;

/**
 * Add Name behavior to an entity.
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
trait NameTrait 
{
    /**
     * Nombre
     * @var string
     * @ORM\Column(type="string")
     */
    private $name;    

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Type
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}