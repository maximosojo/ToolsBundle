<?php

namespace Maximosojo\ToolsBundle\Service\DynamicBuilder\Widget;

use JMS\Serializer\Annotation as JMS;
use Maximosojo\ToolsBundle\Service\DynamicBuilder\Traits\TitleTrait;
use Maximosojo\ToolsBundle\Service\DynamicBuilder\Traits\UriActionTrait;
use Maximosojo\ToolsBundle\Service\DynamicBuilder\Traits\UriActionInterface;

/**
 * Widget para botones
 *
 * @author Máximo Sojo <mxsojo13@gmail.com>
 * @JMS\ExclusionPolicy("ALL");
 */
class ButtonWidget extends BaseWidget implements UriActionInterface
{
    // Tipos de botones
	public const TYPE_BUTTON = "button";
	public const TYPE_SUBMIT = "submit";

    use TitleTrait;
	use UriActionTrait;
    
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
        $this->uriTarget = self::TYPE_TARGET_PUSH_NAMED;
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
