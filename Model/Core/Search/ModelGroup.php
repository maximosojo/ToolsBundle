<?php

namespace Maximosojo\ToolsBundle\Model\Core\Search;

use Doctrine\ORM\Mapping as ORM;
use Maximosojo\ToolsBundle\Traits\ORM\TraitRef;
use Maximosojo\ToolsBundle\Interfaces\SequenceGenerator\ItemReferenceInterface;
use Maximosojo\ToolsBundle\Traits\ORM\EnableableTrait;
use Maximosojo\ToolsBundle\Traits\ORM\Basic\DescriptionTrait;
use Maximosojo\ToolsBundle\Model\ModelStandarMaster;

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