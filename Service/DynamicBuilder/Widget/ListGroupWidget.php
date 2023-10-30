<?php

namespace Maximosojo\ToolsBundle\Service\DynamicBuilder\Widget;

use JMS\Serializer\Annotation as JMS;
use Maximosojo\ToolsBundle\Service\DynamicBuilder\Widget\BaseWidget;
use Maximosojo\ToolsBundle\Service\DynamicBuilder\Widget\ListGroup\ItemWidget;

/**
 * Widget para grupo lista
 *
 * @author Máximo Sojo <mxsojo13@gmail.com>
 * @JMS\ExclusionPolicy("ALL");
 */
class ListGroupWidget extends BaseWidget
{
    /**
     * Items
     * @var array
     * @JMS\Expose
     * @JMS\SerializedName("items")
     */
    protected $items;

    public function __construct()
	{
        parent::__construct("list_group");
    }

    public function addItem(ItemWidget $item)
    {
        $this->items[] = $item;

        return $this;
    }
}
