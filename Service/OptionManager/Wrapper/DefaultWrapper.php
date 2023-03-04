<?php

/*
 * This file is part of the Maximosojo Tools package.
 * 
 * (c) https://maximosojo.github.io/tools-bundle
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maximosojo\ToolsBundle\Service\OptionManager\Wrapper;

/**
 * Wrapper por defecto para guardar valores generales
 *
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
class DefaultWrapper extends BaseWrapper 
{
    public static function getName() {
        return "default";
    }
}
