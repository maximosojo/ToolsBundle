<?php

namespace Maxtoan\ToolsBundle\Model\ObjectManager\ExporterManager\DoctrineORM;

use Doctrine\ORM\Mapping as ORM;
use Maxtoan\ToolsBundle\Traits\Basic\NameTrait;
use Maxtoan\ToolsBundle\Traits\Basic\DescriptionTrait;
use Maxtoan\ToolsBundle\Traits\IdentifiableTrait;

/**
 * Modelo de variables
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

    use IdentifiableTrait;
    use NameTrait;
    use DescriptionTrait;

    public function getTypeVariable()
    {
        return $this->typeVariable;
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
