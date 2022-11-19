<?php

namespace Maximosojo\ToolsBundle\Service\DynamicBuilder\Traits;

use JMS\Serializer\Annotation as JMS;

/**
 * URI de la pagina a cargar al tocar el elemento
 *
 * @author Máximo Sojo <mxsojo13@gmail.com>
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
    
    public function getUriAction(): ?string
    {
        return $this->uriAction;
    }

    public function setUriAction(?string $uriAction)
    {
        $this->uriAction = $uriAction;

        return $this;
    }
}
