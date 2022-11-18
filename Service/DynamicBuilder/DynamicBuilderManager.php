<?php

/*
 * This file is part of the Maximosojo Tools package.
 * 
 * (c) https://maximosojo.github.io/tools-bundle
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maximosojo\ToolsBundle\Service\DynamicBuilder;

use Limenius\Liform\Liform;

/**
 * Manejador de procesos dinamicos
 *
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
class DynamicBuilderManager
{
    /**
     * Serializador de formulario de symfony
     * @var Liform
     */
    private $liform;

    private $items;

    public function addWidget(Widget\BaseWidget $widget)
    {
        $widget->setLiform($this->liform);
        $widget->transform();
        $this->items[] = $widget;
    }

    public function getWidgets()
    {
        return $this->items;
    }

    /**
     * @required
     * @param Liform $liform
     * @return $this
     */
    public function setLiform(Liform $liform)
    {
        $this->liform = $liform;
        return $this;
    }
}
