<?php

namespace Maximosojo\ToolsBundle\Model\Core\Search;

use Doctrine\ORM\Mapping as ORM;
use Maximosojo\ToolsBundle\Traits\TraitRef;
use Maximosojo\ToolsBundle\Interfaces\SequenceGenerator\ItemReferenceInterface;
use Maximosojo\ToolsBundle\Traits\EnableableTrait;
use Maximosojo\ToolsBundle\Model\ModelStandarMaster;

/**
 * Modelo de filtro
 *
 * @author Máximo Sojo <maxsojo13@gmail.com>
 * @ORM\MappedSuperclass()
 */
class ModelFilter extends ModelStandarMaster implements ItemReferenceInterface
{
    const TYPE_INPUT= "input";
    const TYPE_DATE = "date";
    const TYPE_SELECT = "select";
    const TYPE_SELECT2 = "select2";
    const TYPE_SELECT2_ENTITY = "select2Entity";
    
    /**
     * Tipo de filtro (self::TYPE_*)
     * @var integer
     * @ORM\Column(name="type",type="string",length=100)
     */
    protected $type;

    /**
     * Etiqueta
     * @var string
     * @ORM\Column(name="label",type="string",length=200)
     */
    protected $label;

    /**
     * Nombre del modelo
     * @var string
     * @ORM\Column(name="model_name",type="string",length=200,nullable=true)
     */
    protected $modelName;

    /**
     * Parametros adicionales
     * @var array
     * @ORM\Column(name="parameters",type="array")
     */
    protected $parameters;

    /**
     * Roles
     * @var array
     * @ORM\Column(name="rol",type="string",nullable=true)
     */ 
    protected $rol;

    protected $added;

    use TraitRef;
    use EnableableTrait;
    
    /**
     * Set type.
     *
     * @param string $type
     *
     * @return Filter
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set label.
     *
     * @param string $label
     *
     * @return Added
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label.
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set modelName.
     *
     * @param string|null $modelName
     *
     * @return Added
     */
    public function setModelName($modelName = null)
    {
        $this->modelName = $modelName;

        return $this;
    }

    /**
     * Get modelName.
     *
     * @return string|null
     */
    public function getModelName()
    {
        return $this->modelName;
    }

    /**
     * Set parameters.
     *
     * @param array $parameters
     *
     * @return Added
     */
    public function setParameters($parameters)
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * Get parameters.
     *
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    public function getParameter($key,$default = null)
    {
        if(isset($this->parameters[$key])){
            return $this->parameters[$key];
        }
        
        return $default;
    }

    /**
     * Set rol.
     *
     * @param string|null $rol
     *
     * @return Added
     */
    public function setRol($rol = null)
    {
        $this->rol = $rol;

        return $this;
    }

    /**
     * Get rol.
     *
     * @return string|null
     */
    public function getRol()
    {
        return $this->rol;
    }

    /**
     * Set added.
     *
     * @param string $added
     *
     * @return Filter
     */
    public function setAdded($added)
    {
        $this->added = $added;

        return $this;
    }

    /**
     * Get added.
     *
     * @return string
     */
    public function getAdded()
    {
        return $this->added;
    }
}
