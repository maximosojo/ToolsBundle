<?php

namespace Maxtoan\ToolsBundle\Model\Template;

use Doctrine\ORM\Mapping as ORM;

/**
 * Modelo de plantilla
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 * @author Máximo Sojo <maxsojo13@gmail.com>
 * @ORM\MappedSuperclass()
 */
class ModelTemplate implements TemplateInterface
{
    /**
     * Id
     * @ORM\Id
     * @ORM\Column(name="id", type="string", length=36)
     * @ORM\GeneratedValue(strategy="NONE")
     */
    protected $id;

    /**
     * Nombre
     * @var string
     * @ORM\Column(name="name",type="string")
     */
    protected $name;
    
    /**
     * Tipo de plantilla (self::TYPE_*)
     * @var string
     * @ORM\Column(type="string",length=10,nullable=false)
     */
    protected $typeTemplate;
    
    /**
     * Variables que seran pasadas al que renderiza la plantilla (motor de twig)
     * @var Variable
     */
    protected $variables;
    
    /**
     * Parametros que seran pasados al binario que compila la plantilla
     * @var Parameter
     */
    protected $parameters;
    
    /**
     * Contenido del template
     * @var string 
     * @ORM\Column(type="text",nullable=false)
     */
    protected $content;

    public function __construct()
    {
        $this->variables = new \Doctrine\Common\Collections\ArrayCollection();
        $this->parameters = new \Doctrine\Common\Collections\ArrayCollection();
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

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setTypeTemplate($typeTemplate)
    {
        $this->typeTemplate = $typeTemplate;
        return $this;
    }

    public function getTypeTemplate()
    {
        return $this->typeTemplate;
    }

    public function getParameters()
    {
        return $this->parameters;
    }

    public function getVariables()
    {
        return $this->variables;
    }

    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }
    
    public function getContent()
    {
        return $this->content;
    }
}
