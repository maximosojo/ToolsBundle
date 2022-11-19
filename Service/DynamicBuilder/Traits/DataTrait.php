<?php

namespace Maximosojo\ToolsBundle\Service\DynamicBuilder\Traits;

use JMS\Serializer\Annotation as JMS;

/**
 * Elemtno data
 *
 * @author Máximo Sojo <mxsojo13@gmail.com>
 */
trait DataTrait
{
    /**
     * Elemento titulo
     * @var string|null
     * @JMS\Expose
     * @JMS\SerializedName("data")
     */
    protected $data;
    
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }
}
