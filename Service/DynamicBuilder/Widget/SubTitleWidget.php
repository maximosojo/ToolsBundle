<?php

namespace Maximosojo\ToolsBundle\Service\DynamicBuilder\Widget;

use JMS\Serializer\Annotation as JMS;
use Maximosojo\ToolsBundle\Service\DynamicBuilder\Traits\SubTitleTrait;

/**
 * Widget para titulos de paginas
 *
 * @author Máximo Sojo <mxsojo13@gmail.com>
 * @JMS\ExclusionPolicy("ALL");
 */
class SubTitleWidget extends BaseWidget
{
	use SubTitleTrait;

	public function __construct()
	{
        parent::__construct("sub_title");
    }
}
