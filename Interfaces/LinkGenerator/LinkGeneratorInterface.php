<?php

/*
 * This file is part of the Maximosojo Tools package.
 * 
 * (c) https://maximosojo.github.io/tools-bundle
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maximosojo\ToolsBundle\Interfaces\LinkGenerator;

use Maximosojo\ToolsBundle\Service\LinkGenerator\LinkGeneratorService;

/**
 * Interface de generador de link
 * 
 * @author Carlos Mendoza<inhack20@gmail.com>
 */
interface LinkGeneratorInterface
{
    public static function getConfigObjects();
    
    public function getIconsDefinition();
    
    public function setLinkGeneratorService(LinkGeneratorService $linkGeneratorService);
}
