<?php

namespace Maxtoan\ToolsBundle\Model\Core\View\Options;

use Maxtoan\ToolsBundle\Model\Core\View\Options\Attr;

/**
 * Property
 * 
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
class Property
{
    /**
     * Label
     * @var string
     */
    public $label;

    /**
     * Label attr
     * @var array
     */
    public $label_attr;

    /**
     * Value
     * @var string
     */
    public $value;

    /**
     * Value
     * @var array
     */
    public $attr;

    /**
     * @param string $label
     *
     * @return $this
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @param array $label_attr
     *
     * @return $this
     */
    public function setLabelAttr(array $labelAttr = array())
    {
        $this->label_attr = $labelAttr;

        return $this;
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @param array $attr
     *
     * @return $this
     */
    public function setAttr(array $attr = array())
    {
        $this->attr = $attr;

        return $this;
    }
}
