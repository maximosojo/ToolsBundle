<?php

/*
 * This file is part of the TecnoReady Solutions C.A. package.
 * 
 * (c) www.tecnoready.com
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Atechnologies\ToolsBundle\Twig\Extension;

use Atechnologies\ToolsBundle\Service\LinkGenerator\LinkGeneratorService;
use Twig_Extension;

/**
 * Extension de link generador
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class LinkGeneratorExtension extends Twig_Extension
{
    /**
     *
     * @var LinkGeneratorService
     */
    private $linkGeneratorService;
    
    /**
     * getFunctions
     * @author Máximo Sojo maxsojo13@gmail.com <maxtoan at atechnologies>
     * @return array
     */
    public function getFunctions() 
    {
        return [
            new \Twig_SimpleFunction('link_generator', array($this, 'linkGenerator'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('link_generator_url', array($this, 'linkGeneratorUrl'), array('is_safe' => array('html'))),
        ];
    }
    
    /**
     * Genera un link completo para mostrar el objeto
     * @param Entity $entity
     * @param LinkGeneratorService $type
     * @return LinkGeneratorService
     */
    function linkGenerator($entity, $type = LinkGeneratorService::TYPE_LINK_DEFAULT, array $parameters = array()) 
    {
        if($entity === null){
            return "";
        }
        
        return $this->linkGeneratorService->generate($entity, $type, $parameters);
    }

    /**
     * Genera solo la url de el objeto
     * @param type $entity
     * @param type $type
     * @return type
     */
    function linkGeneratorUrl($entity, $type = LinkGeneratorService::TYPE_LINK_DEFAULT, array $parameters = array()) 
    {
        if($entity === null){
            return "";
        }

        return $this->linkGeneratorService->generateOnlyUrl($entity, $type, $parameters);
    }

    /**
     * set Link Generator
     * @author Máximo Sojo maxsojo13@gmail.com <maxtoan at atechnologies>
     * @param  LinkGeneratorService
     */
    public function setLinkGeneratorService(LinkGeneratorService $linkGeneratorService) 
    {
        $this->linkGeneratorService = $linkGeneratorService;

        return $this;
    }

    /**
     * getName
     * @author Máximo Sojo maxsojo13@gmail.com <maxtoan at atechnologies>
     * @return name
     */
    public function getName() 
    {
        return "link_generator_extension";
    }
}
