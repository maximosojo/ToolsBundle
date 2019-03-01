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
 * SoftDeleteableTrait
 *
 * @author Máximo Sojo <maxsojo13@gmail.com> 
 */
trait SoftDeleteableTrait 
{
    /**
     * Fecha de eliminación del objeto
     * @var \DateTime
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    protected $deletedAt;
    
    /**
     * Set deletedAt.
     * @param \Datetime|null $deletedAt
     * @return $this
     */
    public function setDeletedAt(\DateTime $deletedAt = null)
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * Returns deletedAt.
     * @return \DateTime
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * Is deleted?
     * @return bool
     */
    public function isDeleted()
    {
        return null !== $this->deletedAt;
    }
}