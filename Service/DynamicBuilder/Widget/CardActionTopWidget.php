<?php

namespace Maximosojo\ToolsBundle\Service\DynamicBuilder\Widget;

use JMS\Serializer\Annotation as JMS;
use Maximosojo\ToolsBundle\Service\DynamicBuilder\Widget\BaseWidget;
use Maximosojo\ToolsBundle\Service\DynamicBuilder\Traits\IconTrait;
use Maximosojo\ToolsBundle\Service\DynamicBuilder\Traits\TitleTrait;
use Maximosojo\ToolsBundle\Service\DynamicBuilder\Traits\SubTitleTrait;
use Maximosojo\ToolsBundle\Service\DynamicBuilder\Traits\UriActionTrait;
use Maximosojo\ToolsBundle\Service\DynamicBuilder\Traits\UriActionInterface;

/**
 * Widget para titulos de paginas
 *
 * @author Máximo Sojo <mxsojo13@gmail.com>
 * @JMS\ExclusionPolicy("ALL");
 */
class CardActionTopWidget extends BaseWidget implements UriActionInterface
{
    use IconTrait;
    use TitleTrait;
    use SubTitleTrait;
    use UriActionTrait;
    
	public function __construct()
	{
        parent::__construct("card_action_top");
        $this->uriTarget = self::TYPE_TARGET_PUSH_NAMED;
    }
}
