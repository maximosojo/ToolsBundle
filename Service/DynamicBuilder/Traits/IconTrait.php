<?php

namespace Maximosojo\ToolsBundle\Service\DynamicBuilder\Traits;

use JMS\Serializer\Annotation as JMS;

/**
 * Elemento icono
 *
 * @author Máximo Sojo <mxsojo13@gmail.com>
 */
trait IconTrait
{
    /**
     * Elemento titulo
     * @var string|null
     * @JMS\Expose
     * @JMS\SerializedName("icon")
     */
    protected $icon;
    
    public function setIcon(?string $icon)
    {
        $this->icon = $icon;

        return $this;
    }
}
