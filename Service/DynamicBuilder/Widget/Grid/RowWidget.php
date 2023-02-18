<?php

namespace Maximosojo\ToolsBundle\Service\DynamicBuilder\Widget\Grid;

use JMS\Serializer\Annotation as JMS;
use Maximosojo\ToolsBundle\Service\DynamicBuilder\Widget\BaseWidget;
use Maximosojo\ToolsBundle\Service\DynamicBuilder\Traits\ChildrenTrait;

/**
 * Widget para filas
 *
 * @author Máximo Sojo <mxsojo13@gmail.com>
 * @JMS\ExclusionPolicy("ALL");
 */
class RowWidget extends BaseWidget
{
	use ChildrenTrait;

	public function __construct()
	{
        parent::__construct("row");
    }
}
