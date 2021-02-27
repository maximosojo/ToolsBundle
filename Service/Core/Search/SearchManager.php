<?php

namespace Maxtoan\ToolsBundle\Service\Core\Search;

use App\Entity\M\Core\Search\Block;
use App\Entity\M\Core\Search\Added;
use App\Entity\M\Core\Search\Filter;
use Maxtoan\ToolsBundle\Service\BaseService;

/**
 * Manejador de filtros
 *
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
class SearchManager extends BaseService
{
    /**
     * Filtros estandares
     */
    private $standardFilters = "MaxtoanToolsBundle:searchManager:standard.html.twig";

    /**
     * Template para renderizar los filtros
     */
    private $templateFilters = "MaxtoanToolsBundle:searchManager:template.html.twig";
    
    /**
     * @var \Twig_Environment
     */
    private $twig;
    
    public function renderFilterArea($areaName)
    {
        $blocks = $this->getRepository(Block::class)->findByArea($areaName);
        return $this->renderFiltersBlock($blocks);
    }
    
    public function renderFiltersBlock($blocks)
    {
        return $this->twig->render($this->templateFilters,[
            "blocks" => $blocks,
            "searchManager" => $this,
        ]);
    }
    
    /**
     * Renderiza un filtro
     * @param type $groupFilter
     * @param string $filterName
     * @param Filter $filter
     * @return type
     */
    public function renderFilter(Filter $filter)
    {
        $template = $this->twig->loadTemplate($this->standardFilters);
        
        return $template->renderBlock($filter->getType(),[
            "id" => null,
            "label" => $filter->getLabel(),
            "modelName" => $filter->getModelName(),
            "searchManager" => $this,
            "currentFilter" => $filter
        ]);
    }

    /**
     * @required
     */
    public function setTwig(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    public function setStandardFilters($standardFilters)
    {
        $this->standardFilters = $standardFilters;
        return $this;
    }

    public function getStandardFilters()
    {
        return $this->standardFilters;
    }
}