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
 * Add Status behavior to an entity.
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
trait StatusTrait 
{
    /**
     * Status
     * @var string
     * @ORM\Column(type="string")
     */
    private $status;    

    /**
     * Set status
     *
     * @param string $status
     *
     * @return string
     */
    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }
}