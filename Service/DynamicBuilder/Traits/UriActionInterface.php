<?php

/*
 * This file is part of the Maximosojo Tools package.
 * 
 * (c) https://maximosojo.github.io/tools-bundle
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maximosojo\ToolsBundle\Service\DynamicBuilder\Traits;

/**
 * UriActionInterface
 * @author Máaximo Sojo <maxsojo13@gmail.com>
 */
interface UriActionInterface 
{
	// Tipos de target
    public const TYPE_TARGET_PUSH_NAMED = "push_named";
    public const TYPE_TARGET_PUSH_REPLACE_NAMED = "push_replace_named";
    
    public function setUriAction(?string $uriAction);
    public function setUriIcon(?string $uriIcon);
    public function setUriTitle(?string $uriTitle);
    public function setUriTarget($uriTarget);
}
