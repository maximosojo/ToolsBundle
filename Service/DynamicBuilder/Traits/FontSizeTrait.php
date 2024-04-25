<?php

namespace Maximosojo\ToolsBundle\Service\DynamicBuilder\Traits;

use JMS\Serializer\Annotation as JMS;

/**
 * Elemento FontSizeTrait
 *
 * @author Máximo Sojo <mxsojo13@gmail.com>
 */
trait FontSizeTrait
{
    /**
     * fontSize
     * @var double|null
     * @JMS\Expose
     * @JMS\SerializedName("font_size")
     */
    protected $fontSize;
    
    public function setFontSize(?float $fontSize)
    {
        $this->fontSize = $fontSize;

        return $this;
    }
}
