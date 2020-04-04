<?php

namespace Maxtoan\ToolsBundle\Model\Mailer;

use Doctrine\ORM\Mapping as ORM;

/**
 * Componente de correo
 *
 * @author Máximo Sojo <maxsojo13@gmail.com>
 * @ORM\MappedSuperclass()
 */
class ModelComponent
{
    const TYPE_COMPONENT_BASE = "base";
    const TYPE_COMPONENT_HEADER = "header";
    const TYPE_COMPONENT_BODY = "body";
    const TYPE_COMPONENT_FOOTER = "footer";

    /**
     * @var string
     * @ORM\Column(name="id", type="string", length=255, nullable=false)
     * @ORM\Id
     */
    protected $id;

    /**
     * @ORM\Column(name="type_component",type="string",length=30,nullable=false)
     */
    protected $typeComponent;
    
    /**
     * Titulo del componente para ubicarlo facilmente
     * @ORM\Column(name="title",type="string",length=150,nullable=false)
     */
    protected $title;
    
    /**
     * @ORM\Column(name="body",type="text",nullable=false)
     */
    protected $body;
    
    /** 
     * Idioma
     * @ORM\Column(name="locale",type="string",length=10,nullable=false)
     */
    protected $locale;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->bases = new \Doctrine\Common\Collections\ArrayCollection();
        $this->headers = new \Doctrine\Common\Collections\ArrayCollection();
        $this->bodys = new \Doctrine\Common\Collections\ArrayCollection();
        $this->footers = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function getTypeComponent()
    {
        return $this->typeComponent;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getBody()
    {
        return $this->body;
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

    public function setTypeComponent($typeComponent)
    {
        $this->typeComponent = $typeComponent;
        return $this;
    }

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }

    public function setLocale($locale)
    {
        $this->locale = $locale;
        return $this;
    }
    
    /**
     * Add basis
     *
     * @param \Maxtoan\ToolsBundle\Interfaces\Mailer\TemplateInterface $basis
     *
     * @return Component
     */
    public function addBase(\Maxtoan\ToolsBundle\Interfaces\Mailer\TemplateInterface $basis)
    {
        $basis->setBase($this);
        $this->bases[] = $basis;

        return $this;
    }

    /**
     * Remove basis
     *
     * @param \Maxtoan\ToolsBundle\Interfaces\Mailer\TemplateInterface $basis
     */
    public function removeBase(\Maxtoan\ToolsBundle\Interfaces\Mailer\TemplateInterface $basis)
    {
        $this->bases->removeElement($basis);
    }

    /**
     * Get bases
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBases()
    {
        return $this->bases;
    }

    /**
     * Add header
     *
     * @param \Maxtoan\ToolsBundle\Interfaces\Mailer\TemplateInterface $header
     *
     * @return Component
     */
    public function addHeader(\Maxtoan\ToolsBundle\Interfaces\Mailer\TemplateInterface $header)
    {
        $header->setHeader($this);
        $this->headers[] = $header;

        return $this;
    }

    /**
     * Remove header
     *
     * @param \Maxtoan\ToolsBundle\Interfaces\Mailer\TemplateInterface $header
     */
    public function removeHeader(\Maxtoan\ToolsBundle\Interfaces\Mailer\TemplateInterface $header)
    {
        $this->headers->removeElement($header);
    }

    /**
     * Get headers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Add body
     *
     * @param \Maxtoan\ToolsBundle\Interfaces\Mailer\TemplateInterface $body
     *
     * @return Component
     */
    public function addBody(\Maxtoan\ToolsBundle\Interfaces\Mailer\TemplateInterface $body)
    {
        $body->setBody($this);
        $this->bodys[] = $body;

        return $this;
    }

    /**
     * Remove body
     *
     * @param \Maxtoan\ToolsBundle\Interfaces\Mailer\TemplateInterface $body
     */
    public function removeBody(\Maxtoan\ToolsBundle\Interfaces\Mailer\TemplateInterface $body)
    {
        $this->bodys->removeElement($body);
    }

    /**
     * Get bodys
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBodys()
    {
        return $this->bodys;
    }

    /**
     * Add footer
     *
     * @param \Maxtoan\ToolsBundle\Interfaces\Mailer\TemplateInterface $footer
     *
     * @return Component
     */
    public function addFooter(\Maxtoan\ToolsBundle\Interfaces\Mailer\TemplateInterface $footer)
    {
        $footer->setFooter($this);
        $this->footers[] = $footer;

        return $this;
    }

    /**
     * Remove footer
     *
     * @param \Maxtoan\ToolsBundle\Interfaces\Mailer\TemplateInterface $footer
     */
    public function removeFooter(\Maxtoan\ToolsBundle\Interfaces\Mailer\TemplateInterface $footer)
    {
        $this->footers->removeElement($footer);
    }

    /**
     * Get footers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFooters()
    {
        return $this->footers;
    }
        
    public function __toString()
    {
        return $this->title?:"-";
    }
    
    /**
     * Retorna los estatus del componente
     * @return array
     */
    public static function getTypesLabels()
    {
        return [
            "email.component.type.base" => self::TYPE_COMPONENT_BASE,
            "email.component.type.header" => self::TYPE_COMPONENT_HEADER,
            "email.component.type.body" => self::TYPE_COMPONENT_BODY,
            "email.component.type.footer" => self::TYPE_COMPONENT_FOOTER,
        ];
    }
    
    /**
     * Retorna la etiqueta del estatus
     * @return string
     */
    public function getTypeLabel()
    {
        $type = $this->getTypeComponent();
        $types = self::getTypesLabels();
        return $types === null ? : array_search($type,$types);
    }
}
