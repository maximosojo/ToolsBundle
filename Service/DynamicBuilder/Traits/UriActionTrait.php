<?php

namespace Maximosojo\ToolsBundle\Service\DynamicBuilder\Traits;

use JMS\Serializer\Annotation as JMS;

/**
 * URI de la pagina a cargar al tocar el elemento
 *
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
trait UriActionTrait
{
    /**
     * URI de la pagina a cargar al tocar el elemento
     * @var string|null
     * @JMS\Expose
     * @JMS\SerializedName("uri_action")
     */
    protected $uriAction;

    /**
     * ICONO de la pagina a cargar al tocar el elemento
     * @var string|null
     * @JMS\Expose
     * @JMS\SerializedName("uri_icon")
     */
    protected $uriIcon;

    /**
     * TITLE de la pagina a cargar al tocar el elemento
     * @var string|null
     * @JMS\Expose
     * @JMS\SerializedName("uri_title")
     */
    protected $uriTitle;

    /**
     * URI de la pagina a cargar al tocar el elemento
     * @var string|null
     * @JMS\Expose
     * @JMS\SerializedName("uri_target")
     */
    protected $uriTarget;

    public function setUriAction(?string $uriAction)
    {
        $this->uriAction = $uriAction;

        return $this;
    }

    public function setUriIcon(?string $uriIcon)
    {
        $this->uriIcon = $uriIcon;

        return $this;
    }

    public function setUriTitle(?string $uriTitle)
    {
        $this->uriTitle = $uriTitle;

        return $this;
    }

    public function setUriTarget($uriTarget)
    {
        $this->uriTarget = $uriTarget;

        return $this;
    }
}
