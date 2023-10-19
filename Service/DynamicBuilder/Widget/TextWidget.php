<?php

namespace Maximosojo\ToolsBundle\Service\DynamicBuilder\Widget;

use JMS\Serializer\Annotation as JMS;
use Maximosojo\ToolsBundle\Service\DynamicBuilder\Widget\BaseWidget;

/**
 * Widget para textos planos
 *
 * @author Máximo Sojo <mxsojo13@gmail.com>
 * @JMS\ExclusionPolicy("ALL");
 */
class TextWidget extends BaseWidget
{
    /**
     * Texto
     * @var string|null
     * @JMS\Expose
     * @JMS\SerializedName("content")
     */
    protected $content;

	public function __construct($content = null)
	{
        parent::__construct("text");
        $this->content = $content??'';
    }

    public function setcontent(string $content)
    {
        $this->content = $content;

        return $this;
    }
}
