<?php

namespace Maxtoan\ToolsBundle\Component\Liform\Constraints;

/**
 * No se permite valor vacio
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class NotBlank extends Constraint
{
    public $message = 'This value should not be blank.';
}
