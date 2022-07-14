<?php

/*
 * This file is part of the Maxtoan Tools package.
 * 
 * (c) https://maximosojo.github.io/tools-bundle
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maxtoan\ToolsBundle\Model\Sms;

use Doctrine\ORM\Mapping as ORM;

/**
 * Modelo mensajes
 * 
 * @author Máximo Sojo <maxsojo13@gmail.com>
 * @ORM\MappedSuperclass()
 */
class ModelMessage
{
    // Estatus
    const STATUS_FAILED = 'FAILED';
    const STATUS_READY = 'READY';
    const STATUS_PROCESSING = 'PROCESSING';
    const STATUS_COMPLETE = 'COMPLETE';
    const STATUS_CANCELLED = 'CANCELLED';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;
    
    /**
     * Destinatario
     * @var type 
     * @ORM\Column(name="recipient",type="string",length=30)
     */
    protected $recipient;

    /**
     * Contenido
     * @var type 
     * @ORM\Column(name="content",type="string")
     */
    protected $content;
    
    /**
     * Prioridad
     * @var type 
     * @ORM\Column(name="priority",type="integer")
     */
    protected $priority;

    /**
     * Estatus
     * @var type 
     * @ORM\Column(name="status",type="string",length=20)
     */
    protected $status;
    
    /**
     * Enviado a usuario
     * @var User 
     */
    protected $sentBy;
    
    /**
     * @var \DateTime $sentAt
     *
     * @ORM\Column(name="sent_at", type="datetime", nullable=true)
     */
    protected $sentAt;
    
    /**
     * Fecha de envio
     * @var \DateTime $shippingDate
     * @ORM\Column(name="shipping_date", type="datetime", nullable=false)
     */
    protected $shippingDate;
    
    /**
     * @var string
     *
     * @ORM\Column(name="error_message", type="text", nullable=true)
     */
    protected $errorMessage;
    
    /**
     * @var string $environment
     *
     * @ORM\Column(name="environment", type="string", nullable=true,length=10)
     */
    protected $environment;
    
    /**
     * @var string $transport
     *
     * @ORM\Column(name="transport", type="string", nullable=true,length=50)
     */
    protected $transport;

    /**
     * @var string
     *
     * @ORM\Column(name="transport_history", type="array")
     */
    protected $transportHistory;
    
    /**
     * @var string
     *
     * @ORM\Column(name="retries", type="integer")
     */
    protected $retries;

    public function __construct()
    {
        $this->transportHistory = [];
    }

    public function getId()
    {
        return $this->id;
    }

    public function getRecipient()
    {
        return $this->recipient;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getPriority()
    {
        return $this->priority;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getSentAt()
    {
        return $this->sentAt;
    }

    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    public function getEnvironment()
    {
        return $this->environment;
    }

    public function setRecipient($recipient)
    {
        $this->recipient = $recipient;
        return $this;
    }
    
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    public function setPriority($priority)
    {
        $this->priority = $priority;
        return $this;
    }

    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    public function setSentAt(\DateTime $sentAt)
    {
        $this->sentAt = $sentAt;
        return $this;
    }

    public function setErrorMessage($errorMessage)
    {
        $this->errorMessage = $errorMessage;
        return $this;
    }

    public function setEnvironment($environment)
    {
        $this->environment = $environment;
        return $this;
    }
    
    public function getRetries()
    {
        return $this->retries;
    }

    public function setRetries($retries)
    {
        $this->retries = $retries;
        return $this;
    }
    
    public function getTransport()
    {
        return $this->transport;
    }

    public function getTransportHistory()
    {
        return $this->transportHistory;
    }

    public function setTransport($transport)
    {
        $this->transport = $transport;
        return $this;
    }

    public function addTransportHistory($transportHistory)
    {
        $this->transportHistory[] = $transportHistory;
        return $this;
    }
    
    public function getShippingDate()
    {
        return $this->shippingDate;
    }

    public function setShippingDate(\DateTime $shippingDate)
    {
        $this->shippingDate = $shippingDate;
        return $this;
    }
    
    public function getSentBy()
    {
        return $this->sentBy;
    }

    public function setSentBy($sentBy)
    {
        $this->sentBy = $sentBy;
        return $this;
    }
}
