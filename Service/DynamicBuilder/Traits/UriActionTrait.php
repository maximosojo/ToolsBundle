<?php

namespace Maximosojo\ToolsBundle\Service\DynamicBuilder\Traits;

use JMS\Serializer\Annotation as JMS;
use Maximosojo\ToolsBundle\Service\DynamicBuilder\Traits\UriActionInterface;

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

    public function setUriAction(UriActionInterface $uriAction)
    {
        $this->uriAction = $uriAction;

        return $this;
    }
}
