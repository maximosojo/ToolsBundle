<?php

/*
 * This file is part of the Atechnologies package.
 * 
 * (c) www.atechnologies.com.ve
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Atechnologies\ToolsBundle\Service\Util;

/**
 * Funciones utiles
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class AppUtil 
{
    /**
     * Get id format
     * @author Máximo Sojo maxsojo13@gmail.com <maxtoan at atechnologies>
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

    /**
     * Format icon for extension
     * @author Carlos Mendoza <inhack20@gmail.com>
     * @param  extension
     * @return icon
     */
    public static function iconExtension($extension) 
    {
        $faicon = 'unknown';
        $extensionsAvailables = [
            "zip" => "compressed",
            "rar" => "compressed",
            "csv" => "csv",
            "pdf" => "pdf",
            "txt" => "text",
            "doc" => "word",
            "docx" => "word",
            "xls" => "xls",
            "xlsx" => "xls",
        ];
        if(isset($extensionsAvailables[$extension])){
            $faicon = $extensionsAvailables[$extension];
        }
        $icon = '<i class="fa fa-'.$faicon.'"></i>';
        return $icon;
    }
}
