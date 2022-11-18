<?php

namespace Maximosojo\ToolsBundle\Service\DynamicBuilder\Widget;

use Limenius\Liform\Liform;

/**
 * Base para widgets
 *
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
abstract class BaseWidget
{
    /**
     * Elemnto a renderizar
     * @var string
     */
    protected $widget;

    public function __construct($widgetName)
    {
        $this->widget = $widgetName;
    }

    public function setLiform(Liform $liform)
    {
        return $this;
    }

    public function transform()
    {
    	return $this;
    }
}
