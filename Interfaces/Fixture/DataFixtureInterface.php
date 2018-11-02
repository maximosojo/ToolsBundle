<?php

/*
 * This file is part of the Atechnologies package.
 * 
 * (c) www.atechnologies.com.ve
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Atechnologies\ToolsBundle\Interfaces\Fixture;

/**
 * DataFixtureInterface
 * @author Matías Jiménez matei249@gmail.com <matjimdi at atechnologies>
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
    public function getAlies();
    
}
