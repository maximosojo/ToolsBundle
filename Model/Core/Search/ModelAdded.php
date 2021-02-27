<?php

namespace Maxtoan\ToolsBundle\Model\Core\Search;

use Doctrine\ORM\Mapping as ORM;
use Maxtoan\ToolsBundle\Traits\EnableableTrait;
use Maxtoan\ToolsBundle\Traits\TraitRef;
use Maxtoan\ToolsBundle\Interfaces\SequenceGenerator\ItemReferenceInterface;
use Maxtoan\ToolsBundle\Model\ModelStandarMaster;

/**
 * Modelo de filtro añadido
 *
 * @author Máximo Sojo <maxsojo13@gmail.com>
 * @ORM\MappedSuperclass()
 */
class ModelAdded extends ModelStandarMaster implements ItemReferenceInterface
{
    /**
     * Orden del filtro
     * @var integer
     * @ORM\Column(name="order_filter",type="integer")
     */
    protected $orderFilter = 0;

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

    /**
     * @var ModelAdded
     */
    protected $filter;

    /**
     * @var ModelAdded
     */
    protected $block;

    /**
     * @var ModelAdded
     */
    protected $group;

    use TraitRef;
    use EnableableTrait;

    /**
     * Set orderFilter.
     *
     * @param int $orderFilter
     *
     * @return Added
     */
    public function setOrderFilter($orderFilter)
    {
        $this->orderFilter = $orderFilter;

        return $this;
    }

    /**
     * Get orderFilter.
     *
     * @return int
     */
    public function getOrderFilter()
    {
        return $this->orderFilter;
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
     * Set filter.
     *
     * @param $filter
     *
     * @return Added
     */
    public function setFilter($filter)
    {
        if (is_null($this->label)) {
            $this->label = $filter->getLabel();
        }

        $this->filter = $filter;

        return $this;
    }

    /**
     * Get filter.
     *
     * @return      
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * Set block.
     *
     * @param $block
     *
     * @return Added
     */
    public function setBlock($block)
    {
        $this->block = $block;

        return $this;
    }

    /**
     * Get block.
     *
     * @return \App\Entity\M\Core\Search\Block
     */
    public function getBlock()
    {
        return $this->block;
    }

    /**
     * Set group.
     *
     * @param  $group
     *
     * @return Added
     */
    public function setGroup($group)
    {
        $this->group = $group;

        return $this;
    }

    /**
     * Get group.
     *
     * @return 
     */
    public function getGroup()
    {
        return $this->group;
    }
}
