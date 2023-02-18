<?php

namespace Maximosojo\ToolsBundle\Service\DynamicBuilder\Widget;

use JMS\Serializer\Annotation as JMS;
use Maximosojo\ToolsBundle\Service\DynamicBuilder\Widget\BaseWidget;
use Maximosojo\ToolsBundle\Service\DynamicBuilder\Traits\DataTrait;

/**
 * Widget para imagenes
 *
 * @author Máximo Sojo <mxsojo13@gmail.com>
 * @JMS\ExclusionPolicy("ALL");
 */
class ImageWidget extends BaseWidget
{
	public const FORMAT_JPG = "jpg";
	public const FORMAT_PNG = "png";
	public const FORMAT_GIF = "gif";
	public const FORMAT_SVG = "svg";
	public const FORMAT_BASE64 = "base64";

	/**
     * Elemento titulo
     * @var string|null
     * @JMS\Expose
     * @JMS\SerializedName("format")
     */
    protected $format;

    /**
     * Elemento titulo
     * @var string|null
     * @JMS\Expose
     * @JMS\SerializedName("width")
     */
    protected $width;

    /**
     * Elemento titulo
     * @var string|null
     * @JMS\Expose
     * @JMS\SerializedName("height")
     */
    protected $height;

	use DataTrait;

	public function __construct()
	{
        parent::__construct("image");
    }

    public function setFormat($format)
    {
        $this->format = $format;

        return $this;
    }

    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }
}
