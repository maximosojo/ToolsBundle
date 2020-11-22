<?php

namespace Maxtoan\ToolsBundle\Model\Template;

use Doctrine\ORM\Mapping as ORM;

/**
 * Modelo de parametros
 *
 * @author Máximo Sojo <maxsojo13@gmail.com>
 * @ORM\MappedSuperclass()
 */
class ModelParameter
{
    const TYPE_INT = "INT";
    const TYPE_FLOAT = "FLOAT";
    const TYPE_OBJECT = "OBJECT";
    const TYPE_STRING = "STRING";
    
    /**
     * Nombre
     * @var string
     * @ORM\Column(name="name",type="string")
     */
    protected $name;
    
    /**
     * Descripcion del contenido de la variable
     * @var string
     * @ORM\Column(name="description",type="text")
     */
    protected $description;
    
    /**
     * Tipo de contenido de la variable (self::TYPE_*)
     * @var string
     * @ORM\Column(type="string",length=10,nullable=false)
     */
    protected $typeVariable;

    /**
     * ¿Es requerido el parametro?
     * @var boolean 
     * @ORM\Column(type="boolean")
     */
    protected $required = false;
    
    /**
     * Valor por defecto del parametro
     * @var string
     * @ORM\Column(type="text",nullable=true)
     */
    protected $defaultValue = null;
    
    /**
     * Valor del parametro (sobrescribe el valor por defecto)
     * @var string
     * @ORM\Column(type="text",nullable=true)
     */
    protected $value;

    /**
     * Plantilla asociada
     * @var Template
     */
    protected $template;

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        
        return $this;
    }
    
    public function getDescription()
    {
        return $this->description;
    }

    public function getTypeVariable()
    {
        return $this->typeVariable;
    }

    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    public function setTypeVariable($typeVariable)
    {
        $this->typeVariable = $typeVariable;
        return $this;
    }

    public function getRequired()
    {
        return $this->required;
    }

    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getTemplate()
    {
        return $this->template;
    }

    public function setRequired($required)
    {
        $this->required = $required;
        return $this;
    }

    public function setDefaultValue($defaultValue)
    {
        $this->defaultValue = $defaultValue;
        return $this;
    }

    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }
    
    public function getTypeVariableLabel()
    {
        $type = $this->getTypeVariable();
        return $type === null ? : array_search($type,self::getLabelsTypeVariable());
    }
        
    
    public static function getLabelsTypeVariable() 
    {
        return array(
            "label.parameter.type.int" => self::TYPE_INT,
            "label.parameter.type.float" => self::TYPE_FLOAT,
            "label.parameter.type.object" => self::TYPE_OBJECT,
            "label.parameter.type.string" => self::TYPE_STRING,
        );
    }
}
