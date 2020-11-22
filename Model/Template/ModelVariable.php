<?php

namespace Maxtoan\ToolsBundle\Model\Template;

use Doctrine\ORM\Mapping as ORM;

/**
 * Modelo de parametros
 *
 * @author Máximo Sojo <maxsojo13@gmail.com>
 * @ORM\MappedSuperclass()
 */
class ModelVariable
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
