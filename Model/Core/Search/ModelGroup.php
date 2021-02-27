<?php

namespace Maxtoan\ToolsBundle\Model\Core\Search;

use Doctrine\ORM\Mapping as ORM;
use Maxtoan\ToolsBundle\Traits\TraitRef;
use Maxtoan\ToolsBundle\Interfaces\SequenceGenerator\ItemReferenceInterface;
use Maxtoan\ToolsBundle\Traits\EnableableTrait;
use Maxtoan\ToolsBundle\Traits\Basic\DescriptionTrait;
use Maxtoan\ToolsBundle\Model\ModelStandarMaster;

/**
 * Modelo de grupo de filtro
 *
 * @author Máximo Sojo <maxsojo13@gmail.com>
 * @ORM\MappedSuperclass()
 */
class ModelGroup extends ModelStandarMaster implements ItemReferenceInterface
{
    /**
     * Orden del grupo dentro del area
     * @var integer
     * @ORM\Column(name="order_group",type="integer")
     */
    protected $orderGroup = 0;

    use TraitRef;
    use EnableableTrait;
    use DescriptionTrait;
    
    public function getOrderGroup()
    {
        return $this->orderGroup;
    }

    public function setOrderGroup($orderGroup)
    {
        $this->orderGroup = $orderGroup;
        return $this;
    }
}