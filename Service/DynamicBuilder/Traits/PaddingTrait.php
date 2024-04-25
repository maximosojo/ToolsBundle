<?php

namespace Maximosojo\ToolsBundle\Service\DynamicBuilder\Traits;

use JMS\Serializer\Annotation as JMS;

/**
 * Add PaddingTrait
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
trait PaddingTrait 
{
    /**
     * Elemento tipo paddingTop
     * @var double|null
     * @JMS\Expose
     * @JMS\SerializedName("padding_top")
     */
    protected $paddingTop = 0.0;

    /**
     * Elemento tipo paddingBottom
     * @var double|null
     * @JMS\Expose
     * @JMS\SerializedName("padding_bottom")
     */
    protected $paddingBottom = 0.0;

    /**
     * Elemento tipo paddingLeft
     * @var double|null
     * @JMS\Expose
     * @JMS\SerializedName("padding_left")
     */
    protected $paddingLeft = 0.0;

    /**
     * Elemento tipo paddingRight
     * @var double|null
     * @JMS\Expose
     * @JMS\SerializedName("padding_right")
     */
    protected $paddingRight = 0.0;

    public function setPaddingTop($paddingTop)
    {
        $this->paddingTop = $paddingTop;

        return $this;
    }

    public function setPaddingBottom($paddingBottom)
    {
        $this->paddingBottom = $paddingBottom;

        return $this;
    }

    public function setPaddingLeft($paddingLeft)
    {
        $this->paddingLeft = $paddingLeft;

        return $this;
    }

    public function setPaddingRight($paddingRight)
    {
        $this->paddingRight = $paddingRight;

        return $this;
    }
}