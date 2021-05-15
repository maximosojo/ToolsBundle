<?php

namespace Maxtoan\ToolsBundle\Component\Liform;

/**
 * Convertir validacion a una compatible
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
interface ConstraintsParserInterface
{
    public function parse($constraint);
}
