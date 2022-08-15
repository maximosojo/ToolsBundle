<?php

namespace Maximosojo\ToolsBundle\Service\ObjectManager;

/**
 * Interfaz para configurar elementos a un id y tipo
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
interface ConfigureInterface
{
    public function configure($objectId, $objectType);
}
