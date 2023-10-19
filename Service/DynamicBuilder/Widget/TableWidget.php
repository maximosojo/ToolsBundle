<?php

namespace Maximosojo\ToolsBundle\Service\DynamicBuilder\Widget;

use JMS\Serializer\Annotation as JMS;
use Maximosojo\ToolsBundle\Service\DynamicBuilder\Widget\BaseWidget;
use Maximosojo\ToolsBundle\Service\DynamicBuilder\WidgetInterface;

/**
 * Widget para titulos de paginas
 *
 * @author Máximo Sojo <mxsojo13@gmail.com>
 * @JMS\ExclusionPolicy("ALL");
 */
class TableWidget extends BaseWidget
{
    /**
     * Head de tabla
     * @var array
     * @JMS\Expose
     * @JMS\SerializedName("head")
     */
    protected $head;

    /**
     * Head de tabla
     * @var array
     * @JMS\Expose
     * @JMS\SerializedName("body")
     */
    protected $body;

	public function __construct()
	{
        parent::__construct("table");
    }

    public function addHead(WidgetInterface $item)
    {
        $this->head[] = $item;

        return $this;
    }

    public function addBody(WidgetInterface $item)
    {
        $this->body[] = $item;

        return $this;
    }
}
