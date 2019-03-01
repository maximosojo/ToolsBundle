<?php

/*
 * This file is part of the Máximo Sojo - maxtoan package.
 * 
 * (c) https://maxtoan.github.io/
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maxtoan\ToolsBundle\Service\Util;

/**
 * Util basico en base a libreria BCMath PHP http://php.net/manual/es/book.bc.php
 * 
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
class MathUtil
{
    /**
     * Escala por defecto para formatear a decimales
     */
    const SCALE_DECIMAL = 2;
    
    /**
     * Suma dos numeros decimales
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @param $a
     * @param $b
     * @return Number
     */
    public static function sum($a, $b) 
    {
        return bcadd($a, $b, self::SCALE_DECIMAL);
    }
    
    /**
     * Resta dos numeros decimales
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @param $a
     * @param $b
     * @return Number
     */
    public static function sub($a,$b) 
    {
        return bcsub($a, $b, self::SCALE_DECIMAL);
    }
    
    /**
     * Divide dos numeros
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @param $a
     * @param $b
     * @return Number
     */
    public static function div($a,$b) 
    {
        return bcdiv($a, $b, self::SCALE_DECIMAL);
    }
    
    /**
     * Multiplica dos numero
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @param $a
     * @param $b
     * @return Number
     */
    public static function mul($a,$b) 
    {
        return bcmul($a, $b, self::SCALE_DECIMAL);
    }

    /**
     * Aplica porcentaje
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @param $a
     * @param $b
     * @return Number
     */
    public static function percent($a, $b) 
    {
        $result = bcmul($a, $b, self::SCALE_DECIMAL);
        return bcdiv($result, 100, self::SCALE_DECIMAL);
    }

    /**
     * Verifica si dos numeros son iguales
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @param $a
     * @param $b
     * @return Number
     */
    public static function isEquals($a, $b) 
    {
        return bccomp($a, $b, self::SCALE_DECIMAL) === 0;
    }
}
