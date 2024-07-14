<?php

namespace Maximosojo\ToolsBundle\Service\DynamicBuilder\Widget;

use JMS\Serializer\Annotation as JMS;
use Maximosojo\ToolsBundle\Service\DynamicBuilder\Traits\UriActionInterface;
use Maximosojo\ToolsBundle\Service\DynamicBuilder\Traits\AttrTrait;

/**
 * Widget para botones
 *
 * @author Máximo Sojo <mxsojo13@gmail.com>
 * @JMS\ExclusionPolicy("ALL");
 */
class UriActionWidget extends BaseWidget implements UriActionInterface
{
    /**
     * PATH de la uri
     * @var string|null
     * @JMS\Expose
     * @JMS\SerializedName("path")
     */
    protected $path;

    /**
     * ARGUMENTS de la uri
     * @var array
     * @JMS\Expose
     * @JMS\SerializedName("arguments")
     */
    protected $arguments;

    /**
     * ICONO de la pagina a cargar al tocar el elemento
     * @var string|null
     * @JMS\Expose
     * @JMS\SerializedName("icon")
     */
    protected $icon;

    /**
     * TITLE de la pagina a cargar al tocar el elemento
     * @var string|null
     * @JMS\Expose
     * @JMS\SerializedName("title")
     */
    protected $title;

    /**
     * URI de la pagina a cargar al tocar el elemento
     * @var string|null
     * @JMS\Expose
     * @JMS\SerializedName("target")
     */
    protected $target;

    use AttrTrait;

	public function __construct(?string $path = null, ?string $icon = null)
	{
        $this->path = $path;
        $this->icon = $icon;
        $this->uriTarget = self::TYPE_TARGET_PUSH_NAMED;
    }

    public function setPath(?string $path)
    {
        $this->path = $path;

        return $this;
    }

    public function setArguments(?array $arguments)
    {
        $this->arguments = $arguments;

        return $this;
    }

    public function setIcon(?string $icon)
    {
        $this->icon = $icon;

        return $this;
    }

    public function setTitle(?string $title)
    {
        $this->title = $title;

        return $this;
    }

    public function setTarget($target)
    {
        $this->target = $target;

        return $this;
    }
}
