<?php

namespace Maximosojo\ToolsBundle\Model\Notifier\Mailer;

use Doctrine\ORM\Mapping as ORM;
use Maximosojo\ToolsBundle\Traits\ORM\Basic\ExtraDataTrait;

/**
 * Cola de correo
 *
 * @author Máximo Sojo <maxsojo13@gmail.com>
 * @ORM\MappedSuperclass()
 */
class ModelQueue implements ModelQueueInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="subject", type="text")
     */
    protected $subject;
    
    /**
     * @var string
     *
     * @ORM\Column(name="from_email", type="json", length=255)
     */
    protected $fromEmail;
    
    /**
     * @var string
     *
     * @ORM\Column(name="to_email", type="json")
     */
    protected $toEmail;
    
    /**
     * @var string
     *
     * @ORM\Column(name="body", type="text")
     */
    protected $body;
    
    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255)
     */
    protected $status;
    
    /**
     * @var string $environment
     *
     * @ORM\Column(name="environment", type="string", nullable=true)
     */
    protected $environment;
    
    /**
     * @var string
     *
     * @ORM\Column(name="attachs", type="json")
     */
    protected $attachs;

    use ExtraDataTrait;

    public function __construct()
    {
        $this->extraData = [];
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function getFromEmail()
    {
        return $this->fromEmail;
    }

    public function getToEmail()
    {
        return $this->toEmail;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getEnvironment()
    {
        return $this->environment;
    }

    public function getAttachs()
    {
        return $this->attachs;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    public function setFromEmail($fromEmail)
    {
        $this->fromEmail = $fromEmail;

        return $this;
    }

    public function setToEmail(array $toEmail)
    {
        $this->toEmail = $toEmail;

        return $this;
    }

    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    public function setEnvironment($environment)
    {
        $this->environment = $environment;

        return $this;
    }

    public function setAttachs($attachs)
    {
        $this->attachs = $attachs;

        return $this;
    }
}
