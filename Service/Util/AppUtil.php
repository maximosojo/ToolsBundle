<?php

namespace Maximosojo\ToolsBundle\Service\Util;

use DateTime;

/**
 * Funciones utiles
 *
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
class AppUtil
{    
    /**
     * isCommandLineInterface
     *  
     * @return boolean
     */
    public static function isCommandLineInterface()
    {
        return (php_sapi_name() === 'cli');
    }
}