<?php

namespace Maximosojo\ToolsBundle\Service\DynamicBuilder\Traits;

use JMS\Serializer\Annotation as JMS;

/**
 * Add MarginTrait
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
trait MarginTrait 
{
    /**
     * Elemento tipo marginTop
     * @var double|null
     * @JMS\Expose
     * @JMS\SerializedName("margin_top")
     */
    protected $marginTop = 0.0;

    /**
     * Elemento tipo marginBottom
     * @var double|null
     * @JMS\Expose
     * @JMS\SerializedName("margin_bottom")
     */
    protected $marginBottom = 0.0;

    /**
     * Elemento tipo marginLeft
     * @var double|null
     * @JMS\Expose
     * @JMS\SerializedName("margin_left")
     */
    protected $marginLeft = 0.0;

    /**
     * Elemento tipo marginRight
     * @var double|null
     * @JMS\Expose
     * @JMS\SerializedName("margin_right")
     */
    protected $marginRight = 0.0;

    public function setMarginTop($marginTop)
    {
        $this->marginTop = $marginTop;

        return $this;
    }

    public function setMarginBottom($marginBottom)
    {
        $this->marginBottom = $marginBottom;

        return $this;
    }

    public function setMarginLeft($marginLeft)
    {
        $this->marginLeft = $marginLeft;

        return $this;
    }

    public function setMarginRight($marginRight)
    {
        $this->marginRight = $marginRight;

        return $this;
    }
}