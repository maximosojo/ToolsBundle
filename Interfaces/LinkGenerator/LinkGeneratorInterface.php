<?php

/*
 * This file is part of the Maxtoan Tools package.
 * 
 * (c) https://maxtoan.github.io/tools-bundle
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maxtoan\ToolsBundle\Interfaces\LinkGenerator;

use Maxtoan\ToolsBundle\Service\LinkGenerator\LinkGeneratorService;

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
