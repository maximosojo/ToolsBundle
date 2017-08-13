<?php

namespace Atechnologies\ToolsBundle\Service\LinkGenerator;

/**
 * Interface de generador de link
 * 
 * @author Máximo Sojo maxsojo13@gmail.com <maxtoan in atechnologies>
 */
interface LinkGeneratorInterface
{
    public static function getConfigObjects();
    
    public function getIconsDefinition();
}
