<?php

namespace Maximosojo\ToolsBundle\Service\DynamicBuilder\Traits;

use JMS\Serializer\Annotation as JMS;

/**
 * Elemtno titulo
 *
 * @author Máximo Sojo <mxsojo13@gmail.com>
 */
trait TitleTrait
{
    /**
     * Elemento titulo
     * @var string|null
     * @JMS\Expose
     * @JMS\SerializedName("title")
     */
    protected $title;
    
    public function setTitle(?string $title)
    {
        $this->title = $title;

        return $this;
    }
}
