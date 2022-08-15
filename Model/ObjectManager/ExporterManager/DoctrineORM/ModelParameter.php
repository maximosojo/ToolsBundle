<?php

namespace Maximosojo\ToolsBundle\Model\ObjectManager\ExporterManager\DoctrineORM;

use Doctrine\ORM\Mapping as ORM;

/**
 * Modelo de parametros
 *
 * @author Máximo Sojo <maxsojo13@gmail.com>
 * @ORM\MappedSuperclass()
 */
class ModelParameter extends ModelVariable
{
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
}
