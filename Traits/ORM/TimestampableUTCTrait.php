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
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * TimestampableUTCTrait
 *
 * @author Máximo Sojo <maxsojo13@gmail.com> 
 */
trait TimestampableUTCTrait 
{
   /**
     * Fecha de creación del objeto
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     * Fecha de actualización del objeto
     * @var \DateTime
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated_at", type="datetime")
     */
    protected $updatedAt;

    /**
     * Set createdAt.
     * @param  \DateTime $createdAt
     * @return $this
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Returns createdAt.
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        if ($this->createdAt) {
            $this->createdAt->setTimeZone(new \DateTimeZone($_ENV['APP_DEFAULT_TIMEZONE']));
        }
        
        return $this->createdAt;
    }

    /**
     * Set updatedAt.
     * @param  \DateTime $updatedAt
     * @return $this
     */
    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Returns updatedAt.
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        if ($this->updatedAt) {
            $this->updatedAt->setTimeZone(new \DateTimeZone($_ENV['APP_DEFAULT_TIMEZONE']));
        }
        
        return $this->updatedAt;
    }
}