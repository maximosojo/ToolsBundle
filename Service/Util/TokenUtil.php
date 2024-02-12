<?php

/*
 * This file is part of the Maxtoan Tools package.
 * 
 * (c) https://maxtoan.github.io/common
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maximosojo\ToolsBundle\Service\Util;

/**
 * TokenUtil
 * 
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
class TokenUtil
{
    /**
     * Generador de token
     * 
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @return Token
     */
    public function generate()
    {
        return rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
    }
}
