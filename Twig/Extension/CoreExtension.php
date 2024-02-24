<?php

/*
 * This file is part of the Maximosojo Tools package.
 * 
 * (c) https://maximosojo.github.io/tools-bundle
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maximosojo\ToolsBundle\Twig\Extension;

use Maximosojo\ToolsBundle\Service\Util\MathUtil;
use Maximosojo\ToolsBundle\Service\Util\DateUtil;
use Maximosojo\ToolsBundle\Service\ObjectManager\ObjectDataManager;
use Maximosojo\ToolsBundle\DependencyInjection\ContainerAwareTrait;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Twig\TwigFilter;

/**
 * Extension generic applications
 * 
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
class CoreExtension extends AbstractExtension 
{    
    use ContainerAwareTrait;
    
    /**
     * {@inheritdoc}
     */
    public function getFunctions() 
    {
       return array(
            new TwigFunction('uniqueId', array($this, 'uniqueId'),array('is_safe' => ['html'])),
            new TwigFunction('str_padleft', array($this, 'strpadleft')),
            new TwigFunction('get_parameter', array($this, 'getParameter')),
            new TwigFunction('render_tabs', array($this, 'renderTabs'),array('is_safe' => ['html'])),
            new TwigFunction('render_collapse', array($this, 'renderCollapse'),array('is_safe' => ['html'])),
            new TwigFunction('render_files_generated', array($this, 'renderFilesGenerated'),array('is_safe' => ['html'])),
            new TwigFunction('staticCall', array($this, 'staticCall'))
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
            new TwigFilter('my_number_format', array($this, 'myNumberFormat')),
            new TwigFilter('my_format_date', array($this, 'myFormatDate')),
            new TwigFilter('my_format_date_time', array($this, 'myFormatDateTime')),
            new TwigFilter('json_decode', array($this, 'jsonDecode')),
        );
    }

    public function uniqueId()
    {
        return sprintf("%s%s",$this->strRandom(),$this->strRandom());
    }

    private function strRandom($length = 16)
    {
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle(str_repeat($pool, $length)), 0, $length);
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
        return MathUtil::numberFormat($value, $decimals, ',', '.');
    }

    /**
     * Formateo de fecha
     *  
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @param  \DateTime $date [Fecha a formatear]
     * @param  string $format       [Formato esperado]
     * @return string
     */
    public function myFormatDate($date, $format = "d/m/Y") 
    {
        $dateFormated = null;

        if($date instanceof \DateTime){
            $options = [
                "format" => $format,
                "default_time_zone" => $_ENV['APP_DEFAULT_TIMEZONE']
            ];
            $dateFormated = DateUtil::formatDate($date,$options);
        }

        return $dateFormated;
    }

    /**
     * Formateo de fecha en formato DateTime
     *  
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @param  \DateTime $date [Fecha a formatear]
     * @param  string $format       [Formato esperado]
     * @return string
     */
    public function myFormatDateTime($date, $format = "d/m/Y h:i a") 
    {
        $dateFormated = null;
        if($date instanceof \DateTime){
            $options = [
                "format" => $format,
                "default_time_zone" => $_ENV['APP_DEFAULT_TIMEZONE']
            ];
            $dateFormated = DateUtil::formatDatetime($date,$options);
        }

        return $dateFormated;
    }

    public function jsonDecode(string $json)
    {
        return json_decode($json, true);
    }

    /**
     * Render base tabs
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @param  \Maximosojo\ToolsBundle\Model\Tab\Tab
     * @param  array
     * @return Tabs
     */
    public function renderTabs(\Maximosojo\ToolsBundle\Model\Tab\Tab $tab,array $parameters = []) 
    {
        $parameters["tab"] = $tab;
        return $this->container->get('twig')->render("@MaximosojoTools/tab/tabs.html.twig", 
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
        return $this->container->get('twig')->render("@MaximosojoTools/collapse/collapse.html.twig", 
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
        $objectDataManager = $this->container->get("maximosojo_tools.object_manager");
        return $objectDataManager->exporters()->renderFiles($entity);
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
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName() 
    {
        return 'maximosojo_core_extension';
    }
}