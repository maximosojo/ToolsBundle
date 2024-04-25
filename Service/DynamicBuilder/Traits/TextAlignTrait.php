<?php

namespace Maximosojo\ToolsBundle\Service\DynamicBuilder\Traits;

use JMS\Serializer\Annotation as JMS;

/**
 * Elemento textAlign
 *
 * @author Máximo Sojo <mxsojo13@gmail.com>
 */
trait TextAlignTrait
{
    /**
     * textAlign
     * @var string|null
     * @JMS\Expose
     * @JMS\SerializedName("text_align")
     */
    protected $textAlign;
    
    public function setTextAlign(?string $textAlign)
    {
        $this->textAlign = $textAlign;

        return $this;
    }
}
