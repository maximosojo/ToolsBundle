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

/**
 * Interfaz de optiones
 *
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
interface OptionInterface
{
    public function getKey();

    public function getValue();

    public function setKey($key);

    public function setValue($value);
}
