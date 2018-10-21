<?php

/*
 * This file is part of the Atechnologies package.
 * 
 * (c) www.atechnologies.com.ve
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Atechnologies\ToolsBundle\Interfaces\LinkGenerator;

use Atechnologies\ToolsBundle\Service\LinkGenerator\LinkGeneratorService;

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
