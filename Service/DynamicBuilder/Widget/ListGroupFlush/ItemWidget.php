<?php

namespace Maximosojo\ToolsBundle\Service\DynamicBuilder\Widget\ListGroupFlush;

use JMS\Serializer\Annotation as JMS;
use Maximosojo\ToolsBundle\Service\DynamicBuilder\Widget\BaseWidget;
use Maximosojo\ToolsBundle\Service\DynamicBuilder\Traits\TitleTrait;
use Maximosojo\ToolsBundle\Service\DynamicBuilder\Traits\SubTitleTrait;
use Maximosojo\ToolsBundle\Service\DynamicBuilder\Traits\UriActionTrait;

/**
 * Widget para columnas
 *
 * @author Máximo Sojo <mxsojo13@gmail.com>
 * @JMS\ExclusionPolicy("ALL");
 */
class ItemWidget extends BaseWidget
{
    use TitleTrait;
    use SubTitleTrait;
    use UriActionTrait;

	public function __construct()
	{
        parent::__construct("list_group_flush_item");
    }
}
