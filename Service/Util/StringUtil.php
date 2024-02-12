<?php

/*
 * This file is part of the Máximo Sojo - maxtoan package.
 * 
 * (c) https://maxtoan.github.io/common
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maximosojo\ToolsBundle\Service\Util;

/**
 * Util basico en base a Strings de PHP http://php.net/manual/es/book.strings.php
 * 
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
class StringUtil
{
    /**
     * Generar un id
     * 
     * @author Carlos Mendoza <inhack20@gmail.com>
     * @return id
     */
    public static function getId() 
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 20; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return md5(time() . $randomString);
    }

    /**
     * Slugify
     * 
     * @author  Máximo Sojo <maxsojo13@gmail.com>
     * @param   String $text
     * @return  String $text
     */
    public static function slugify($text)
	{
	  	// replace non letter or digits by -
	  	$text = preg_replace('~[^\pL\d]+~u', '-', $text);

	  	// transliterate
	  	$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

	  	// remove unwanted characters
	  	$text = preg_replace('~[^-\w]+~', '', $text);

	  	// trim
	  	$text = trim($text, '-');

	  	// remove duplicate -
	  	$text = preg_replace('~-+~', '-', $text);

	  	// lowercase
	  	$text = strtolower($text);

	  	if (empty($text)) {
		    return 'n-a';
		}

	  	return $text;
    }
    
    /**
     * Trunca un string
     * @param string $label
     * @param type $truncate
     * @return string
     */
    public static function truncate($label, $truncate = 30, $onlylast = false)
    {
        if (empty($label)) {
            return "...";
        }
        if ((strlen($label) > $truncate) && $onlylast == false) {
            $label = mb_substr($label, 0, $truncate, 'UTF-8') . '...';
        } else if ((strlen($label) > $truncate) && $onlylast == true) {
            $label = substr($label, $truncate * -1);
        }
        return $label;
    }

    /**
     * Genera un número aleatorio de 20 digitos
     * @param type $length
     * @return string
     */
    public static function getRamdomNumber($length = 20)
    {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[mt_rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     * Valida que solo tenga caracteres permitidos
     * @param type $str
     * @param type $allowed Por defecto permite letras de la a-z A-Z 0-9 @
     * @return boolean
     */
    public static function validCharacters($str, $allowed = '/[^A-Za-z0-9@]/')
    {
        $valid = false;
        if (!preg_match($allowed, $str)) { // '/[^a-z\d]/i' should also work.
            $valid = true;
        }
        return $valid;
    }

    /**
     * Prueba que la cadena solo tenga numeros
     * @param type $str
     * @return type
     */
    public static function isOnlyNumber($str)
    {
        if (substr($str, 0, 1) === "+") {
            $str = substr($str, 1, strlen($str));
        }
        return self::validCharacters($str, '/[^0-9]/');
    }

    public static function normalizeRef($ref)
    {
        return str_pad($ref, 20, "0", STR_PAD_LEFT);
    }

    public static function clearAccents($str, array $allowed = [])
    {
        $convert = Array(
            'ä' => 'a',
            'Ä' => 'A',
            'á' => 'a',
            'Á' => 'A',
            'à' => 'a',
            'À' => 'A',
            'ã' => 'a',
            'Ã' => 'A',
            'â' => 'a',
            'Â' => 'A',
            'č' => 'c',
            'Č' => 'C',
            'ć' => 'c',
            'Ć' => 'C',
            'ď' => 'd',
            'Ď' => 'D',
            'ě' => 'e',
            'Ě' => 'E',
            'é' => 'e',
            'É' => 'E',
            'ë' => 'e',
            'í' => 'i',
            'Í' => 'I',
            'ó' => 'o',
            'Ó' => 'O',
            'ú' => 'u',
            'ü' => 'u',
            'Ú' => 'U',
            'ñ' => 'n',
            'Ñ' => 'N',
            'è' => 'e',
            'ö' => 'o',
            'ù' => 'u',
            'Ç' => 'C',
            'ô' => 'o',
            'Ü' => 'U',
            'ì' => 'i',
        );
        foreach ($allowed as $v) {
            if (isset($convert[$v])) {
                unset($convert[$v]);
            }
        }

        $str = strtr($str, $convert);
        return $str;
    }

    public static function nospecial($str, $newstr = '_', $badchars = '')
    {
        $forbidden_chars_to_replace = array(" ", "'", "/", "\\", ":", "*", "?", "\"", "<", ">", "|", "[", "]", ",", ";", "=");
        $forbidden_chars_to_remove = array();
        if (is_array($badchars))
            $forbidden_chars_to_replace = $badchars;
        return str_replace($forbidden_chars_to_replace, $newstr, str_replace($forbidden_chars_to_remove, "", $str));
    }

    /**
     * Limpia un texto de caracteres especiales y elimina los espacios
     * @param type $string
     * @return type
     */
    public static function clean($string, $allowed = '/[^A-Za-z0-9\-]/', $spacer = "")
    {
        $string = str_replace(' ', $spacer, $string); // Replaces all spaces with hyphens.
        return preg_replace($allowed, $spacer, $string); // Removes special chars.
    }

    /**
     * Limpia todos los emois o caractes que no sean ASCII
     * @param type $text
     * @return type
     */
    public static function clearEmojis($text)
    {
        return preg_replace('/[[:^print:]]/', '', $text);
    }

    /**
     * ¿el string esta vacio?
     * @param type $value
     * @return boolean
     */
    public static function isEmpty($value)
    {
        if ($value === null) {
            return true;
        }
        if (empty($value)) {
            return true;
        }
        //Si la longitud es menor que 2, se considera vacio
        if (is_string($value) && strlen($value) < 2) {
            return true;
        }
        return false;
    }

    /**
     * Genera un salto seguro para generacion de contrasenas
     * @return string
     */
    public static function generateSalt()
    {
        return rtrim(str_replace('+', '.', base64_encode(random_bytes(32))), '=');
    }

    /**
     * Normaliza un path para eliminar caracteres de mas /
     * @param type $path
     * @return boolean
     */
    public static function pathNormalize($path)
    {
        if (!empty($path)) {
            $prefix = "";
            if ($path[0] === "/") {
                $prefix = "/";
            }
            $path = str_replace('\\', '/', $path);
            $blocks = preg_split('#/#', $path, null, PREG_SPLIT_NO_EMPTY);
            $res = array();

            while (list($k, $block) = each($blocks)) {
                switch ($block) {
                    case '.':
                        if ($k == 0)
                            $res = explode('/', path_normalize(getcwd()));
                        break;
                    case '..';
                        if (!$res)
                            return false;
                        array_pop($res);
                        break;
                    default:
                        $res[] = $block;
                        break;
                }
            }
            return $prefix . implode('/', $res);
        }
        return $path;
    }
    
    /**
     * Elimina los caracteres que no son ASCII
     * @param type $string
     * @return type
     */
    public static function clearNoASCII($string)
    {
        $string = utf8_encode($string);
        $ascii = unpack("C*",$string);
        //https://ascii.cl/es/
        //Eliminar los caracteres estandares que esten fuera de la tabla ASCII
        foreach ($ascii as $key => $v) {
            if($v < 20 || $v > 126){
                unset($ascii[$key]);
            }
        }
        $string = pack("C*",...$ascii);
        return $string;
    }

    /**
     * Limpia solo los caracteres pasados en la variable $forbidden_chars_to_replace
     * @param type $str
     * @param type $newstr
     * @param array $forbidden_chars_to_replace
     * @return type
     */
    public static function clearSpecialChars($str, $newstr = ' ', array $forbidden_chars_to_replace)
    {
        $forbidden_chars_to_remove = array();
        return str_replace($forbidden_chars_to_replace, $newstr, str_replace($forbidden_chars_to_remove, "", $str));
    }
}
