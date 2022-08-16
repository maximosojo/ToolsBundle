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
 * IpTraceableTrait
 *
 * @author Máximo Sojo <maxsojo13@gmail.com> 
 */
trait IpTraceableTrait 
{
   /**
     * @var string $createdIp
     *
     * @Gedmo\IpTraceable(on="create")
     * @ORM\Column(type="string", name="created_ip",length=45, nullable=true)
     */
    protected $createdIp;
    
    /**
     * @var string $updatedIp
     *
     * @Gedmo\IpTraceable(on="update")
     * @ORM\Column(type="string", name="updated_ip",length=45, nullable=true)
     */
    protected $updatedIp;

    /**
     * Set createdIp
     *
     * @param string $createdIp
     * @return IpTraceableTrait
     */
    public function setCreatedIp($createdIp) 
    {
        $this->createdIp = $createdIp;

        return $this;
    }

    /**
     * Get createdIp
     *
     * @return string 
     */
    public function getCreatedIp() 
    {
        return $this->createdIp;
    }

    /**
     * Set updatedIp
     *
     * @param string $updatedIp
     * @return IpTraceableTrait
     */
    public function setUpdatedIp($updatedIp) 
    {
        $this->updatedIp = $updatedIp;

        return $this;
    }

    /**
     * Get updatedIp
     *
     * @return string 
     */
    public function getUpdatedIp() 
    {
        return $this->updatedIp;
    }
}