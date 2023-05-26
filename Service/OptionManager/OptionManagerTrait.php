<?php

/*
 * This file is part of the Maximosojo Tools package.
 * 
 * (c) https://maximosojo.github.io/tools-bundle
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maximosojo\ToolsBundle\Service\OptionManager;

use Maximosojo\ToolsBundle\Service\OptionManager\OptionManagerInterface;

/**
 * Trait de manejador de opciones
 *
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
trait OptionManagerTrait
{
    protected $optionManager;

    /**
     * @required
     */
    public function setOptionManager(OptionManagerInterface $optionManager)
    {
        $this->optionManager = $optionManager;
    }
}