<?php

/*
 * This file is part of the Maxtoan Tools package.
 * 
 * (c) https://maxtoan.github.io/tools-bundle
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maxtoan\ToolsBundle\Twig\Extension;

use Maxtoan\ToolsBundle\Service\Util\StringUtil;

/**
 * Extension generic applications
 * 
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
class CoreExtension extends \Twig_Extension 
{    
    use \Maxtoan\ToolsBundle\DependencyInjection\ContainerAwareTrait;
    
    /**
     * {@inheritdoc}
     */
    public function getFunctions() 
    {
       return array(
            new \Twig_SimpleFunction('breadcrumb', array($this, 'breadcrumb'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('str_padleft', array($this, 'strpadleft')),
            new \Twig_SimpleFunction('get_parameter', array($this, 'getParameter')),
            new \Twig_SimpleFunction('render_tabs', array($this, 'renderTabs'),array('is_safe' => ['html'])),
            new \Twig_SimpleFunction('render_collapse', array($this, 'renderCollapse'),array('is_safe' => ['html']))
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
     * Breadcrumb
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @param  array
     * @return Breadcrumb
     */
    public function breadcrumb($args = []) 
    {
        $parameters = array();
        $args = func_get_args();
        $icon = null;
        foreach ($args as $key => $arg) {
            if (empty($arg)) {
                continue;
            }
            $item = new \stdClass();
            $item->link = null;
            $item->label = null;
            if (is_array($arg)) {
                $count = count($arg);
                if ($count > 1) {
                    throw new \LogicException('The array elment must be one, count');
                }
                foreach ($arg as $key => $value) {
                    if ($key == 'icon') {
                        $icon = $value;
                    }else{
                        $item->link = $key;
                        $item->label = $value;                        
                    }
                }
            } else {
                $item->label = $arg;
            }
            if ($item->link != null || $item->label != null ) {
                $parameters[] = $item;                
            }
        }
        
        $data = [
            'breadcrumb' => $parameters,
            'icon'       => $icon
        ];

        $route = realpath(__DIR__ . '/../../Resources/views/');
        return $this->container->get('templating')->render($route.'/breadcrumb/breadcrumb.html.twig', $data);
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
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName() 
    {
        return 'maxtoan_core_extension';
    }
}
