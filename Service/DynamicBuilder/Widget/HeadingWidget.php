<?php

namespace Maximosojo\ToolsBundle\Service\DynamicBuilder\Widget;

use JMS\Serializer\Annotation as JMS;
use Maximosojo\ToolsBundle\Service\DynamicBuilder\Widget\BaseWidget;
use Maximosojo\ToolsBundle\Service\DynamicBuilder\Traits\TitleTrait;

/**
 * Widget para Heading
 *
 * @author Máximo Sojo <mxsojo13@gmail.com>
 * @JMS\ExclusionPolicy("ALL");
 */
class HeadingWidget extends BaseWidget
{
    public const TAG_H1 = "h1";
    public const TAG_H2 = "h2";
    public const TAG_H3 = "h3";
    public const TAG_H4 = "h4";
    public const TAG_H5 = "h5";
    public const TAG_H6 = "h6";
    public const TAG_SPAN = "span";
    public const TAG_P = "p";

    /**
     * Elemento tag
     * @var string|null
     * @JMS\Expose
     * @JMS\SerializedName("tag")
     */
    protected $tag = self::TAG_H2;

    use TitleTrait;

	public function __construct()
	{
        parent::__construct("heading");
    }

    public function setTag(string $tag)
    {
        $this->tag = $tag;

        return $this;
    }
}
