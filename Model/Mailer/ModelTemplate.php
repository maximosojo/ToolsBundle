<?php

namespace Maximosojo\ToolsBundle\Model\Mailer;

use Doctrine\ORM\Mapping as ORM;

/**
 * Plantilla de correo
 * 
 * @author Máximo Sojo <maxsojo13@gmail.com>
 * @ORM\MappedSuperclass()
 */
class ModelTemplate
{
    const STATUS_PUBLISHED = "published";
    const STATUS_UNPUBLISHED = "unpublished";
    
    /**
     * @var string
     * @ORM\Column(name="id", type="string", length=255, nullable=false)
     * @ORM\Id
     */
    protected $id;

    /**
     * @ORM\Column(name="status",type="string",length=30,nullable=false)
     */
    protected $status;

    /**
     * Titulo del correo para identificarlo apidamente (no lleva parametros)
     * @ORM\Column(name="title",type="string",length=150,nullable=false)
     */
    protected $title;

    /**
     * Asunto del correo (esto puede llevar parametros)
     * @ORM\Column(name="subject",type="text",nullable=false)
     */
    protected $subject;
    
    /**
     * @var ModelComponent
     */
    protected $base;
    /**
     * @var ModelComponent
     */
    protected $header;
    /**
     * @var ModelComponent
     */
    protected $body;
    /**
     * @var ModelComponent
     */
    protected $footer;
    /**
     * @ORM\Column(name="locale",type="string",length=10,nullable=false)
     */
    protected $locale;

    public function getStatus()
    {
        return $this->status;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getBase()
    {
        return $this->base;
    }

    public function getHeader()
    {
        return $this->header;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function getFooter()
    {
        return $this->footer;
    }

    public function getLocale()
    {
        return $this->locale;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    public function setBase(ModelComponent $base)
    {
        $this->base = $base;
        return $this;
    }

    public function setHeader(ModelComponent $header)
    {
        $this->header = $header;
        return $this;
    }

    public function setBody(ModelComponent $body)
    {
        $this->body = $body;
        $this->body->setTitle($this->title);
        return $this;
    }

    public function setFooter(ModelComponent $footer)
    {
        $this->footer = $footer;
        return $this;
    }

    public function setLocale($locale)
    {
        $this->locale = $locale;
        if($this->body){
            $this->body->setLocale($this->locale);
        }
        return $this;
    }
    
    public function getSubject()
    {
        return $this->subject;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }
        
    public function __toString()
    {
        return $this->title?:"-";
    }
    
    /**
     * Retorna los estatus del componente
     * @return array
     */
    public static function getStatusLabels()
    {
        return [
            "email.status.published" => self::STATUS_PUBLISHED,
            "email.status.unpublished" => self::STATUS_UNPUBLISHED,
        ];
    }
    
    /**
     * Retorna la etiqueta del estatus
     * @return string
     */
    public function getStatusLabel()
    {
        $type = $this->getStatus();
        $types = self::getStatusLabels();
        return $types === null ? : array_search($type,$types);
    }
}
