<?php

namespace Maximosojo\ToolsBundle\Service\DynamicBuilder\Widget;

use JMS\Serializer\Annotation as JMS;
use Maximosojo\ToolsBundle\Service\DynamicBuilder\Widget\BaseWidget;
use Maximosojo\ToolsBundle\Service\DynamicBuilder\Traits\TitleTrait;
use Maximosojo\ToolsBundle\Service\DynamicBuilder\Traits\UriActionTrait;
use Maximosojo\ToolsBundle\Service\DynamicBuilder\Traits\AttrTrait;
use Maximosojo\ToolsBundle\Service\DynamicBuilder\Traits\PaddingTrait;
use Maximosojo\ToolsBundle\Service\DynamicBuilder\Traits\MarginTrait;

/**
 * Widget para botones
 *
 * @author Máximo Sojo <mxsojo13@gmail.com>
 * @JMS\ExclusionPolicy("ALL");
 */
class ButtonWidget extends BaseWidget
{
    // Tipos de botones
	public const TYPE_BUTTON = "button";
	public const TYPE_SUBMIT = "submit";

    use TitleTrait;
    use UriActionTrait;
	use AttrTrait;
	use PaddingTrait;
	use MarginTrait;
    
	/**
     * Elemento tipo [button,submit]
     * @var string|null
     * @JMS\Expose
     * @JMS\SerializedName("type")
     */
    protected $type = self::TYPE_SUBMIT;

    /**
     * Elemento tipo disabled
     * @var boolean|null
     * @JMS\Expose
     * @JMS\SerializedName("disabled")
     */
    protected $disabled = false;

	public function __construct()
	{
        parent::__construct("button");
    }

    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    public function setDisabled($disabled)
    {
        $this->disabled = $disabled;

        return $this;
    }
}
