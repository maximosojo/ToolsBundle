<?php

namespace Maximosojo\ToolsBundle\Service\ObjectManager\ExporterManager\Engine;

use Maximosojo\ToolsBundle\Model\ObjectManager\ExporterManager\TemplateInterface;

/**
 * Interfas de adaptador de motor
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
interface EngineInterface
{
    public function render(TemplateInterface $template,array $variables);
    
    public function compile($filename,$string,array $parameters);
    
    public function getExtension();
    
    public function getDefaultParameters();

}
