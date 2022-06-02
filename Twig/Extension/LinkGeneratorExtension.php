<?php

/*
 * This file is part of the TecnoReady Solutions C.A. package.
 * 
 * (c) www.tecnoready.com
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maxtoan\ToolsBundle\Twig\Extension;

use Maxtoan\ToolsBundle\Service\LinkGenerator\LinkGeneratorService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Extension de link generador
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class LinkGeneratorExtension extends AbstractExtension
{
    /**
     *
     * @var LinkGeneratorService
     */
    private $linkGeneratorService;
    
    /**
     * getFunctions
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @return array
     */
    public function getFunctions() 
    {
        return [
            new TwigFunction('link_generator', array($this, 'linkGenerator'), array('is_safe' => array('html'))),
            new TwigFunction('link_is_granted', array($this, 'linkIsGranted'), array('is_safe' => array('html'))),
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
    function linkIsGranted($entity, $role = "ROLE_USER") 
    {
        if($entity === null){
            return "";
        }

        $parameters["role"] = $role;
        return $this->linkGeneratorService->generate($entity, null, $parameters);
    }

    /**
     * set Link Generator
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @param  LinkGeneratorService
     */
    public function setLinkGeneratorService(LinkGeneratorService $linkGeneratorService) 
    {
        $this->linkGeneratorService = $linkGeneratorService;

        return $this;
    }

    /**
     * getName
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @return name
     */
    public function getName() 
    {
        return "link_generator_extension";
    }
}
