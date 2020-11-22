<?php

/*
 * This file is part of the Maxtoan Tools package.
 * 
 * (c) https://maximosojo.github.io/tools-bundle
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maxtoan\ToolsBundle\Twig\Extension;

use Maxtoan\Common\Util\StringUtil;
use Maxtoan\ToolsBundle\Service\ObjectManager\ObjectDataManager;
use Maxtoan\ToolsBundle\DependencyInjection\ContainerAwareTrait;

/**
 * Extension generic applications
 * 
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
class CoreExtension extends \Twig_Extension 
{    
    use ContainerAwareTrait;
    
    private $exporterManager;

    /**
     * {@inheritdoc}
     */
    public function getFunctions() 
    {
       return array(
            new \Twig_SimpleFunction('str_padleft', array($this, 'strpadleft')),
            new \Twig_SimpleFunction('get_parameter', array($this, 'getParameter')),
            new \Twig_SimpleFunction('render_tabs', array($this, 'renderTabs'),array('is_safe' => ['html'])),
            new \Twig_SimpleFunction('render_collapse', array($this, 'renderCollapse'),array('is_safe' => ['html'])),
            new \Twig_SimpleFunction('render_files_generated', array($this, 'renderFilesGenerated'),array('is_safe' => ['html'])),
            new \Twig_SimpleFunction('staticCall', array($this, 'staticCall'))
        );
    }
    
    /**
     *     getFilters
     *     @author Máximo Sojo <maxsojo13@gmail.com>
     *     @date        2017-01-10
     *     @anotherdate 2017-01-10T13:35:06-0430
     */
    public function getFilters() 
    {
        return array(
            new \Twig_SimpleFilter('myNumberFormat', array($this, 'myNumberFormat')),
            new \Twig_SimpleFilter('myFormatDateTime', array($this, 'myFormatDateTime')),
            new \Twig_SimpleFilter('myFormatDate', array($this, 'myFormatDate'))
        );
    }

    /**
     * Add the str_pad left php function
     *
     * @param  string $string
     * @param  int $pad_lenght
     * @param  string $pad_string
     * @return mixed
     */
    public function strpadleft($string, $pad_lenght, $pad_string = " ")
    {
        return str_pad($string, $pad_lenght, $pad_string, STR_PAD_LEFT);
    }
    
    /**
     * getParameter Obtener parametros de container
     *  
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @return Parameters
     */
    public function getParameter($parameter = null)
    {
        return $this->container->getParameter($parameter);
    }

    /**
     * Filtro para formatear numero.
     * @param type $value
     * @param type $decimals
     * @return type
     */
    public function myNumberFormat($value, $decimals = 2) 
    {
        return StringUtil::numberFormat($value, $decimals, ',', '.');
    }

    /**
     * Formateo de fecha en formato DateTime
     *  
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @param  $myFormatDate [Fecha a formatear]
     * @param  string $format       [Formato esperado]
     * @return DateTime
     */
    public function myFormatDateTime($myFormatDate, $format = "d-m-Y h:i a") 
    {
        $dateFormated = "";
        if ($myFormatDate instanceof \DateTime) {
            $dateFormated = $myFormatDate->format($format);
        }

        return $dateFormated;
    }

    /**
     * Formateo de fecha
     *  
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @param  $myFormatDate [Fecha a formatear]
     * @param  string $format       [Formato esperado]
     * @return Date
     */
    public function myFormatDate($myFormatDate, $format = "d-m-Y") 
    {
        $dateFormated = "";
        if ($myFormatDate instanceof \DateTime) {
            $dateFormated = $myFormatDate->format($format);
        }
        
        return $dateFormated;
    }

    /**
     * Render base tabs
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @param  \Maxtoan\ToolsBundle\Model\Tab\Tab
     * @param  array
     * @return Tabs
     */
    public function renderTabs(\Maxtoan\ToolsBundle\Model\Tab\Tab $tab,array $parameters = []) 
    {
        $parameters["tab"] = $tab;
        return $this->container->get('templating')->render("MaxtoanToolsBundle:tab:tabs.html.twig", 
            $parameters
        );
    }

    /**
     * Render base tabs
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @param  array
     * @return Collapse
     */
    public function renderCollapse(array $parameters = []) 
    {
        $parameters["id"] = "_collapse_".sha1($parameters['title']);
        return $this->container->get('templating')->render("MaxtoanToolsBundle:collapse:collapse.html.twig", 
            $parameters
        );
    }

    /**
     * Renderiza la lista de archivos generados
     * @param $entity
     * @return type
     */
    public function renderFilesGenerated($entity) 
    {
        $objectDataManager = $this->container->get(ObjectDataManager::class);
        $exporterManager = $objectDataManager->exporters();
        return $exporterManager->renderFiles($entity);
    }

    /**
     * Llama un metodo estatico de una clase
     * @param type $class
     * @param type $function
     * @param type $args
     * @return type
     */
    public function staticCall($class, $function, $args = array())
    {
        if (class_exists($class) && method_exists($class, $function))
            return call_user_func_array(array($class, $function), $args);
        return null;
    }

    /**
     * ExporterManager
     *
     * @param   ExporterManager  $exporterManager
     * @required
     */
    public function setExporterManager(ExporterManager $exporterManager)
    {
        $this->exporterManager = $exporterManager;
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName() 
    {
        return 'maxtoan_core_extension';
    }
}