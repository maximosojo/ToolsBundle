<?php

namespace Maxtoan\ToolsBundle\Model\Core\Search;

use Doctrine\ORM\Mapping as ORM;
use Maxtoan\ToolsBundle\Traits\EnableableTrait;
use Maxtoan\ToolsBundle\Traits\Basic\DescriptionTrait;
use Maxtoan\ToolsBundle\Model\ModelStandarMaster;

/**
 * Bloque de filtros
 *
 * @author Máximo Sojo <maxsojo13@gmail.com>
 * @ORM\MappedSuperclass()
 */
class ModelBlock extends ModelStandarMaster
{   
    /**
     * Area donde se rendizara el bloque
     * @var string
     * @ORM\Column(name="area",type="string",length=100)
     */
    protected $area;

    /**
     * Orden del bloque
     * @var integer
     * @ORM\Column(name="order_block",type="integer")
     */
    protected $orderBlock = 0;
    
    /**
     * Parametros extras
     * @var integer
     * @ORM\Column(name="parameters",type="json_array")
     */
    protected $parameters;

    /**
     * @var ModelBlock
     */
    protected $addeds;

    use EnableableTrait;
    use DescriptionTrait;
    
    public function __construct()
    {
        $this->parameters = [];
    }

    /**
     * Set area.
     *
     * @param string $area
     *
     * @return Block
     */
    public function setArea($area)
    {
        $this->area = $area;

        return $this;
    }

    /**
     * Get area.
     *
     * @return string
     */
    public function getArea()
    {
        return $this->area;
    }

    /**
     * Set orderBlock.
     *
     * @param int $orderBlock
     *
     * @return Block
     */
    public function setOrderBlock($orderBlock)
    {
        $this->orderBlock = $orderBlock;

        return $this;
    }

    /**
     * Get orderBlock.
     *
     * @return int
     */
    public function getOrderBlock()
    {
        return $this->orderBlock;
    }

    /**
     * Set parameters.
     *
     * @param array $parameters
     *
     * @return Block
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

    public function getFiltersByGroup(ModelGroup $group)
    {
        $filters = [];
        foreach ($this->addeds as $filterAdded) {
            $filter = clone($filterAdded->getFilter());
            if($filterAdded->getGroup() !== null){
                $filterGroup = $filterAdded->getGroup();
            }else{
                $filterGroup = $filter->getGroup();
            }

            if($filterGroup !== $group){
                continue;
            }

            //Reemplazar los filtros locales
            if($filterAdded->getModelName() !== null){
                $filter->setModelName($filterAdded->getModelName());
            }

            if($filterAdded->getLabel() !== null){
                $filter->setLabel($filterAdded->getLabel());
            }

            if($filterAdded->getRol() !== null){
                $filter->setRol($filterAdded->getRol());
            }

            $filter->setAdded($filterAdded);
            $filters[] = $filter;
        }

        return $filters;
    }

    public function getGroupsFilters()
    {
        $groups = [];
        foreach ($this->addeds as $added) {
            if($added->getGroup() !== null){
                $group = $added->getGroup();
            }else{
                $group = $added->getFilter()->getGroup();
            }
            if(in_array($group, $groups)){
                continue;
            }
            $groups[] = $group;
        }
        return $groups;
    }
}
