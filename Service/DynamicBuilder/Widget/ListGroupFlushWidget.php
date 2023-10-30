<?php

namespace Maximosojo\ToolsBundle\Service\DynamicBuilder\Widget;

use JMS\Serializer\Annotation as JMS;
use Maximosojo\ToolsBundle\Service\DynamicBuilder\Widget\BaseWidget;
use Maximosojo\ToolsBundle\Service\DynamicBuilder\Widget\ListGroupFlush\ItemWidget;

/**
 * Widget para grupo lista flush
 *
 * @author Máximo Sojo <mxsojo13@gmail.com>
 * @JMS\ExclusionPolicy("ALL");
 */
class ListGroupFlushWidget extends BaseWidget
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
        parent::__construct("list_group_flush");
    }

    public function addItem(ItemWidget $item)
    {
        $this->items[] = $item;

        return $this;
    }
}
