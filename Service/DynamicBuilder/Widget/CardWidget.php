<?php

namespace Maximosojo\ToolsBundle\Service\DynamicBuilder\Widget;

use JMS\Serializer\Annotation as JMS;
use Maximosojo\ToolsBundle\Service\DynamicBuilder\Widget\BaseWidget;
use Maximosojo\ToolsBundle\Service\DynamicBuilder\Traits\IconTrait;
use Maximosojo\ToolsBundle\Service\DynamicBuilder\Traits\TitleTrait;
use Maximosojo\ToolsBundle\Service\DynamicBuilder\Traits\SubTitleTrait;
use Maximosojo\ToolsBundle\Service\DynamicBuilder\Traits\UriActionTrait;
use Maximosojo\ToolsBundle\Service\DynamicBuilder\Traits\MarginTrait;
use Maximosojo\ToolsBundle\Service\DynamicBuilder\Traits\PaddingTrait;

/**
 * Widget para titulos de paginas
 *
 * @author Máximo Sojo <mxsojo13@gmail.com>
 * @JMS\ExclusionPolicy("ALL");
 */
class CardWidget extends BaseWidget
{
    use IconTrait;
    use TitleTrait;
    use SubTitleTrait;
    use UriActionTrait;
    use MarginTrait;
    use PaddingTrait;

	public function __construct()
	{
        parent::__construct("card");
    }
}
