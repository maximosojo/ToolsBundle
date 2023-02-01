<?php

/*
 * This file is part of the Maximosojo Tools package.
 * 
 * (c) https://maximosojo.github.io/tools-bundle
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maximosojo\ToolsBundle\Interfaces\Fixture;

/**
 * DataFixtureInterface
 * @author Matías Jiménez <matei249@gmail.com>
 */
interface DataFixtureInterface 
{
    /**
     * Traducción del fixture
     */
    public function getNameTrans();
    
    /**
     * Nombre del fixture
     */
    public function getAlias();    
}
