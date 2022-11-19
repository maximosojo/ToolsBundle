<?php

namespace Maximosojo\ToolsBundle\Service\DynamicBuilder\Widget;

use JMS\Serializer\Annotation as JMS;
use Maximosojo\ToolsBundle\Service\DynamicBuilder\Traits\TitleTrait;
use Maximosojo\ToolsBundle\Service\DynamicBuilder\Traits\SubTitleTrait;
use Maximosojo\ToolsBundle\Service\DynamicBuilder\Traits\UriActionTrait;

/**
 * Widget para titulos de paginas
 *
 * @author Máximo Sojo <mxsojo13@gmail.com>
 * @JMS\ExclusionPolicy("ALL");
 */
class CardWidget extends BaseWidget
{
    use TitleTrait;
    use SubTitleTrait;
    use UriActionTrait;

	public function __construct()
	{
        parent::__construct("card");
    }
}
