<?php

/*
 * This file is part of the TecnoCreaciones package.
 * 
 * (c) www.tecnocreaciones.com
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maximosojo\ToolsBundle\Interfaces\SequenceGenerator;

/**
 * Interfas de generador de secuencia
 * 
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
interface SequenceGeneratorInterface 
{
    /**
     * Retorna las clases que esta manejando el generador de secuencia
     */
    public function getClassMap();
}
