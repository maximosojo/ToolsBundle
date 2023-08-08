<?php

/*
 * This file is part of the Maximosojo Tools package.
 * 
 * (c) https://maximosojo.github.io/tools-bundle
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maximosojo\ToolsBundle\Model\Paginator;

use Pagerfanta\Pagerfanta as BasePagerfanta;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Router;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Pagerfanta base para serialización
 */
class Paginator extends BasePagerfanta
{
    /**
     * @deprecated
     */
    use \Maximosojo\ToolsBundle\DependencyInjection\ContainerAwareTrait;

    /**
     * $router
     * @var $router
     */
    protected $router;

    /**
     * $route
     * @var $route
     */
    protected $route = null;
    
    /**
     * $defaultFormat
     * @var $defaultFormat
     */
    protected $defaultFormat = self::FORMAT_ARRAY_DEFAULT;
    
    /**
     * $draw
     * @var integer
     */
    protected $draw = 1;

    /**
     *
     * @var \Symfony\Component\HttpFoundation\Request 
     */
    protected $request;

    /**
     * Devuelve un formato estandar de trabajo (ng-table)
     */
    const FORMAT_ARRAY_DEFAULT = 'default';
    
    /**
     * Devuelve un formato estandar de trabajo
     */
    const FORMAT_ARRAY_STANDARD = 'standard';
    
    /**
     * Devuelve un formato para que pueda ser leido por el plugin DataTables de jQuery
     */
    const FORMAT_ARRAY_DATA_TABLES = 'dataTables';

    /**
     * Formato de select2 (https://select2.org/data-sources/ajax)
     */
    const FORMAT_ARRAY_SELECT2 = 'select2';
    
    private $formatArray = array(
        self::FORMAT_ARRAY_DEFAULT, self::FORMAT_ARRAY_DATA_TABLES, self::FORMAT_ARRAY_STANDARD, self::FORMAT_ARRAY_SELECT2
    );
            
    /**
     * Formato por defecto, compatible con ng-tables
     * @param type $route
     * @param array $parameters
     * @return type
     */
    public function formatToArrayDefault($route = null, array $parameters = array(), array $options = []) 
    {
        $links = array(
            'self' => array('href' => ''),
            'first' => array('href' => ''),
            'last' => array('href' => ''),
            'next' => array('href' => ''),
            'previous' => array('href' => ''),
        );

        $paginator = array(
            'getNbResults' => $this->getNbResults(),
            'getCurrentPage' => $this->getCurrentPage(),
            'getNbPages' => $this->getNbPages(),
            'getMaxPerPage' => $this->getMaxPerPage(),
        );
        
        $results = $this->getCurrentPageResults();
        if(!is_array($results)){
            $results = $this->getCurrentPageResults()->getArrayCopy();
        }

        return array(
            '_links' => $this->getLinks($route,$parameters),
            '_embedded' => array(
                'results' => $results,
                'paginator' => $paginator
            ),
        );
    }
            
    /**
     * Formato estandarizado y sencillo
     * @param type $route
     * @param array $parameters
     * @return type
     */
    public function formatToArrayStandard($route = null, array $parameters = array(), array $options = [])
    {
        $links = array(
            'self' => array('href' => ''),
            'first' => array('href' => ''),
            'last' => array('href' => ''),
            'next' => array('href' => ''),
            'previous'  => array('href' => ''),
        );

        $paginator = array(
            'currentPage' => $this->getCurrentPage(),
            'maxPerPage' => $this->getMaxPerPage(),
            'totalPages' => $this->getNbPages(),
            'totalResults' => $this->getNbResults(),
        );
        
        $results = $this->getCurrentPageResults();
        if(!is_array($results)){
            $results = $this->getCurrentPageResults()->getArrayCopy();
        }

        return array(
            'links' => $this->getLinks($route,$parameters),
            'meta' => $paginator,
            'data' => $results,
        );
    }
    
    /**
     * Formato de paginacion de datatables
     * @param type $route
     * @param array $parameters
     * @return type
     */
    public function formatToArrayDataTables($route = null, array $parameters = array(), array $options = []) 
    {
        $results = $this->getCurrentPageResults()->getArrayCopy();

        $data = array(
            'draw' => $this->draw,
            'recordsTotal' => $this->getNbResults(),
            'recordsFiltered' => $this->getNbResults(),
            'data' => $results,
            '_links' => $this->getLinks($route,$parameters),
        );

        return $data;
    }

    function formatToArraySelect2($route = null,array $parameters = array(),array $options = [])
    {
        $links = array(
            'self'  => array('href' => ''),
            'first' => array('href' => ''),
            'last'  => array('href' => ''),
            'next'  => array('href' => ''),
            'previous'  => array('href' => ''),
        );
        $paginator = array(
                        'more' => $this->hasNextPage(),
                        'currentPage' => $this->getCurrentPage(),
                        'maxPerPage' => $this->getMaxPerPage(),
                        'totalPages' => $this->getNbPages(),
                        'totalResults' => $this->getNbResults(),
                    );
        
        $pageResult = $this->getCurrentPageResults();
        if(is_array($pageResult)){
            $results = $pageResult;
        }else{
            $results = $this->getCurrentPageResults()->getArrayCopy();
        }

        $r = $results;
        if(!empty($options["to_object_callback"])){
            $r = [];
            foreach ($results as $result) {
                $r[] = $options["to_object_callback"]($result);
            }
        }

        return array(
            'links' => $this->getLinks($route,$parameters),
            'pagination' => $paginator,
            'results' => $r,
        );
    }
    
    /**
     * Convierte los resultados de la pagina actual en array
     * @param type $route
     * @param array $parameters
     * @param type $format
     * @return type
     */
    public function toArray($route = null,array $parameters = array(),$format = null,array $options = []) 
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            "to_object_callback"  => null,
        ]);
        $options = $resolver->resolve($options);

        if($format === null){
            $format = $this->defaultFormat;
        }

        if(in_array($format, $this->formatArray)){
            $method = 'formatToArray'.ucfirst($format);
            return $this->$method($route,$parameters,$options);
        }
    }
    
    /**
     * Genera una url
     * @param type $route
     * @param array $parameters
     * @return type
     */
    protected function generateUrl($route,array $parameters)
    {
        $route = "";
        if ($this->container) {
            $route = $this->container->get('router')->generate($route, $parameters, Router::ABSOLUTE_URL);
        }

        return $route;
    }
    
    /**
     * Formato de data a devolver
     * @param type $defaultFormat
     * @return \
     */
    public function setDefaultFormat($defaultFormat) 
    {
        $this->defaultFormat = $defaultFormat;
        
        return $this;
    }
    
    /**
     * Genera los links de navegacion entre una y otra pagina
     * @param type $route
     * @param array $parameters
     * @return type
     */
    protected function getLinks($route,array $parameters = array())
    {
        $links = array();
        if($route != null){
            $baseParams = $_GET;
            unset($baseParams["page"]);
            $parameters = array_merge($parameters,$baseParams);
            $links['first']['href'] = $this->generateUrl($route, array_merge($parameters, array('page' => 1)));
            $links['self']['href'] = $this->generateUrl($route, array_merge($parameters, array('page' => $this->getCurrentPage())));
            $links['last']['href'] = $this->generateUrl($route, array_merge($parameters, array('page' => $this->getNbPages())));
            if($this->hasPreviousPage()){
                $links['previous']['href'] = $this->generateUrl($route, array_merge($parameters, array('page' => $this->getPreviousPage())));
            }
            if($this->hasNextPage()){
                $links['next']['href'] = $this->generateUrl($route, array_merge($parameters, array('page' => $this->getNextPage())));
            }
        }
        
        return $links;
    }
    
    /**
     * Establece el request actual para calculos en los formaters
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function setRequest(\Symfony\Component\HttpFoundation\Request $request) 
    {
        $this->request = $request;
        
        if(self::FORMAT_ARRAY_DATA_TABLES == $this->defaultFormat){
            //Ejemplo start(20) / length(10) = 2
            $start = (int)$request->get("start",0);//Elemento inicio
            $length = (int)$request->get("length",10);//Cantidad de elementos por pagina
            $this->draw = $request->get("draw",  $this->draw) + 1;//No cache
            
            if($start > 0){
                $page = (int)($start / $length);
                $page = $page + 1;
            }else{
                $page = 1;
            }
            if(!is_int($length)){
                $length = 10;
            }
            if(!is_int($page)){
                $page = 1;
            }
            $this->setCurrentPage($page);
            $this->setMaxPerPage($length);
        }else if(self::FORMAT_ARRAY_STANDARD == $this->defaultFormat){
            $maxPerPage = (int)$request->get("maxPerPage",10);//Elemento inicio
            $page = (int)$request->get("page",1);//Elemento inicio
            $this->setMaxPerPage($maxPerPage);
            $this->setCurrentPage($page);
        }        
    }

    public function setRouter($router)
    {
        $this->router = $router;
    }
}
