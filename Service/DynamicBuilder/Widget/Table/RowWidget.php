<?php

namespace Maximosojo\ToolsBundle\Service\DynamicBuilder\Widget\Table;

use JMS\Serializer\Annotation as JMS;
use Maximosojo\ToolsBundle\Service\DynamicBuilder\Widget\BaseWidget;
use Maximosojo\ToolsBundle\Service\DynamicBuilder\WidgetInterface;

/**
 * Widget para titulos de paginas
 *
 * @author Máximo Sojo <mxsojo13@gmail.com>
 * @JMS\ExclusionPolicy("ALL");
 */
class RowWidget extends BaseWidget
{
    /**
     * Head de tabla
     * @var array
     * @JMS\Expose
     * @JMS\SerializedName("content")
     */
    protected $content;

	public function __construct()
	{
        parent::__construct("table_row");
    }

    public function addContent(WidgetInterface $item)
    {
        $this->content[] = $item;

        return $this;
    }
}
