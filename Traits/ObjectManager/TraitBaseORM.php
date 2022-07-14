<?php

namespace Maxtoan\ToolsBundle\Traits\ObjectManager;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * Base de modelos ORM
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
trait TraitBaseORM
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;
    
    /**
     * @var \DateTime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at",type="datetime")
     */
    protected $createdAt;

    /**
     * @var string $createdFromIp
     *
     * @Gedmo\IpTraceable(on="create")
     * @ORM\Column(type="string", name="created_from_ip",length=45, nullable=true)
     */
    protected $createdFromIp;
    
    /**
     * Descripcion del evento
     * @var string 
     * @ORM\Column(name="description",type="text",nullable=true)
     */
    protected $description;
    
    /**
     * Navegador usado
     * @var string
     * @ORM\Column(name="user_agent",type="text") 
     */
    protected $userAgent;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string",length=100)
     */
    protected $objectId;
    /**
     * @var string
     *
     * @ORM\Column(type="string",length=50)
     */
    protected $objectType;
    
    public function getId()
    {
        return $this->id;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getCreatedFromIp()
    {
        return $this->createdFromIp;
    }
    
    public function getDescription()
    {
        return $this->description;
    }

    public function getUserAgent()
    {
        return $this->userAgent;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function setCreatedFromIp($createdFromIp)
    {
        $this->createdFromIp = $createdFromIp;
        return $this;
    }
    
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;
        return $this;
    }
    
    public function getObjectId()
    {
        return $this->objectId;
    }

    public function getObjectType()
    {
        return $this->objectType;
    }

    public function setObjectId($objectId)
    {
        $this->objectId = $objectId;
        return $this;
    }

    public function setObjectType($objectType)
    {
        $this->objectType = $objectType;
        return $this;
    }
}
