<?php

namespace Maxtoan\ToolsBundle\Twig\Extension;

use Twig_SimpleFunction;
use Maxtoan\ToolsBundle\Service\Core\Search\SearchManager;

/**
 * Extension para filtros
 *
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
class SearchExtension extends \Twig_Extension 
{
    /**
     * @var SearchManager
     */
    private $searchManager;
    
    use \Symfony\Component\DependencyInjection\ContainerAwareTrait;
    
    public function getFunctions() 
    {
        return array(
            new Twig_SimpleFunction('renderFilterArea', array($this, 'renderFilterArea'),array('is_safe' => array('html'))),
        );
    }
    /**
     * Renderiza filtros de un area
     * @param type $areaName
     */
    public function renderFilterArea($areaName)
    {
        return $this->searchManager->renderFilterArea($areaName);
    }

    /**
     * setSearchManager
     * @param   SearchManager  $searchManager
     * @required
     */
    public function setSearchManager(SearchManager $searchManager)
    {
        $this->searchManager = $searchManager;
    }

    public function getName()
    {
        return "search";
    }
}