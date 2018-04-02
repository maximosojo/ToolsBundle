<?php

/*
 * This file is part of the TecnoReady Solutions C.A. package.
 * 
 * (c) www.tecnoready.com
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Atechnologies\ToolsBundle\Model\LinkGenerator;

use Atechnologies\ToolsBundle\Service\LinkGenerator\LinkGeneratorService;

/**
 * Base de item de generador de link
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
abstract class LinkGeneratorItem implements LinkGeneratorItemInterface 
{
    /**
     *
     * @var LinkGeneratorService 
     */
    protected $linkGeneratorService;
 
    public function getIconsDefinition() 
    {
        return [];
    }
    
    public function setLinkGeneratorService(LinkGeneratorService $linkGeneratorService) 
    {
        $this->linkGeneratorService = $linkGeneratorService;
        return $this;
    }
    
    protected function generateUrl($route, $parameters = array(), $referenceType = \Symfony\Component\Routing\Generator\UrlGeneratorInterface::ABSOLUTE_PATH)
    {
        return $this->linkGeneratorService->generateUrl($route, $parameters, $referenceType);
    }
}
