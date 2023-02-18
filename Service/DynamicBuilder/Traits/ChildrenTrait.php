<?php

namespace Maximosojo\ToolsBundle\Service\DynamicBuilder\Traits;

use JMS\Serializer\Annotation as JMS;

/**
 * Elemtno children
 *
 * @author Máximo Sojo <mxsojo13@gmail.com>
 */
trait ChildrenTrait
{
    /**
     * Elemento titulo
     * @var array
     * @JMS\Expose
     * @JMS\SerializedName("children")
     */
    protected $children;
    
    public function addChildren($children)
    {
        $this->children[] = $children;

        return $this;
    }
}
