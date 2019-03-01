<?php

/*
 * This file is part of the Atechnologies package.
 * 
 * (c) https://maxtoan.github.io/tools-bundle
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maxtoan\ToolsBundle\Service\Util;

/**
 * Funciones utiles
 * 
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class AppUtil 
{
    /**
     * Get id format
     * @author Carlos Mendoza <inhack20@gmail.com>
     * @return id
     */
    static function getId() 
    {
        $length = 20;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return md5(time() . $randomString);
    }
}
