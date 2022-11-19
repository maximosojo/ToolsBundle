<?php

namespace Maximosojo\ToolsBundle\Service\DynamicBuilder\Traits;

use JMS\Serializer\Annotation as JMS;

/**
 * Elemento sub titulo
 *
 * @author Máximo Sojo <mxsojo13@gmail.com>
 */
trait SubTitleTrait
{
    /**
     * Sub titulo
     * @var string|null
     * @JMS\Expose
     * @JMS\SerializedName("sub_title")
     */
    protected $subTitle;
    
    public function setSubTitle(?string $subTitle)
    {
        $this->subTitle = $subTitle;

        return $this;
    }
}
