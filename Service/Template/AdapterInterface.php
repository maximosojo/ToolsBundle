<?php

namespace Maxtoan\ToolsBundle\Service\Template;

use Maxtoan\ToolsBundle\Model\Template\TemplateInterface;

/**
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
interface AdapterInterface
{
    public function render(TemplateInterface $template,array $variables);
    
    public function compile($filename,$string,array $parameters);
    
    public function getExtension();
    
    public function getDefaultParameters();

}
