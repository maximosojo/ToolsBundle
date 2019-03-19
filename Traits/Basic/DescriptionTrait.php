<?php

/*
 * This file is part of the Maxtoan Tools package.
 * 
 * (c) https://maxtoan.github.io/tools-bundle
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maxtoan\ToolsBundle\Traits\Basic;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trair de nombre
 * 
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
trait DescriptionTrait
{
    /**
     * Description
     * @var string
     * @ORM\Column(type="string")
     */
    private $description;

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Shortcut
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
}