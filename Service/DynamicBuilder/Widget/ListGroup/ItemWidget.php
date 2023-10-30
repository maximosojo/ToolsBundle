<?php

namespace Maximosojo\ToolsBundle\Service\DynamicBuilder\Widget\ListGroup;

use JMS\Serializer\Annotation as JMS;
use Maximosojo\ToolsBundle\Service\DynamicBuilder\Widget\BaseWidget;

/**
 * Widget para columnas
 *
 * @author Máximo Sojo <mxsojo13@gmail.com>
 * @JMS\ExclusionPolicy("ALL");
 */
class ItemWidget extends BaseWidget
{
    /**
     * Contenido
     * @var string
     * @JMS\Expose
     * @JMS\SerializedName("content")
     */
    protected $content;

	public function __construct()
	{
        parent::__construct("list_group_item");
    }

    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }
}
