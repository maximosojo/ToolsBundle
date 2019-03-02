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
 * Util basico en base a Strings de PHP http://php.net/manual/es/book.strings.php
 * 
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
class StringUtil
{
    /**
     * Escala por defecto para formatear a decimales
     */
    const SCALE_DECIMAL = 2;
    
    /**
     * Formatear un número con los millares agrupados y valores por defecto
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @param $number
     * @param int $decimals [Número de puntos decimales.]
     * @param string $dec_point [Separador para los decimales.]
     * @param string $thousands_sep [Separador para los millares.]
     * @return number_format
     */
    public static function numberFormat($number, int $decimals = self::SCALE_DECIMAL, string $dec_point = "," , string $thousands_sep = ".")
    {
        $number = number_format($number,$decimals,$dec_point,$thousands_sep);

        return $number;
    }
}
