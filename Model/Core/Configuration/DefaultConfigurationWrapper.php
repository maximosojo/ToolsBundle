<?php

namespace Maxtoan\ToolsBundle\Model\Core\Configuration;

/**
 * Configuracion wraper por defecto para guardar valores generales
 *
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
class DefaultConfigurationWrapper extends ConfigurationWrapper 
{
    public static function getName()
    {
        return "DEFAULT";
    }
}