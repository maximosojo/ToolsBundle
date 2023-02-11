<?php

namespace Maximosojo\ToolsBundle\Service\DynamicBuilder\Widget;

use JMS\Serializer\Annotation as JMS;
use Maximosojo\ToolsBundle\Service\DynamicBuilder\Traits\TitleTrait;

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

    // Tipos de target
    public const TYPE_TARGET_PUSH_NAMED = "push_named";
    public const TYPE_TARGET_PUSH_REPLACE_NAMED = "push_replace_named";

	use TitleTrait;
    
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

    /**
     * URI de la pagina a cargar al tocar el elemento
     * @var string|null
     * @JMS\Expose
     * @JMS\SerializedName("uri_action")
     */
    protected $uriAction;

    /**
     * URI de la pagina a cargar al tocar el elemento
     * @var string|null
     * @JMS\Expose
     * @JMS\SerializedName("uri_target")
     */
    protected $uriTarget = self::TYPE_TARGET_PUSH_NAMED;

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

    public function setUriAction($uriAction)
    {
        $this->uriAction = $uriAction;

        return $this;
    }

    public function setUriTarget($uriTarget)
    {
        $this->uriTarget = $uriTarget;

        return $this;
    }
}
