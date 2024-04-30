<?php

namespace Maximosojo\ToolsBundle\Service\DynamicBuilder\Widget;

use JMS\Serializer\Annotation as JMS;
use Maximosojo\ToolsBundle\Service\DynamicBuilder\Widget\BaseWidget;
use Maximosojo\ToolsBundle\Service\DynamicBuilder\Traits\TitleTrait;
use Maximosojo\ToolsBundle\Service\DynamicBuilder\Traits\TextAlignTrait;
use Maximosojo\ToolsBundle\Service\DynamicBuilder\Traits\FontSizeTrait;
use Maximosojo\ToolsBundle\Service\DynamicBuilder\Traits\MarginTrait;

/**
 * Widget para titulos de paginas
 *
 * @author Máximo Sojo <mxsojo13@gmail.com>
 * @JMS\ExclusionPolicy("ALL");
 */
class TitleWidget extends BaseWidget
{
    use TitleTrait;
    use TextAlignTrait;
	use FontSizeTrait;
    use MarginTrait;

	public function __construct()
	{
        parent::__construct("title");
    }
}
