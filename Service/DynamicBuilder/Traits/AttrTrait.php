<?php

namespace Maximosojo\ToolsBundle\Service\DynamicBuilder\Traits;

use JMS\Serializer\Annotation as JMS;

/**
 * Elemtno attr
 *
 * @author Máximo Sojo <mxsojo13@gmail.com>
 */
trait AttrTrait
{
    /**
     * Elemento titulo
     * @var array|null
     * @JMS\Expose
     * @JMS\SerializedName("attr")
     */
    protected $attr;
    
    public function setAttr($key, $value)
    {
        $this->attr[$key] = $value;

        return $this;
    }

    public function setAttrs(?array $attr)
    {
        $this->attr = $attr;

        return $this;
    }
}
