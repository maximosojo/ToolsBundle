<?php

/*
 * This file is part of the Atechnologies package.
 * 
 * (c) www.atechnologies.com.ve
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Atechnologies\ToolsBundle\Twig\Extension;

/**
 * Extension tabs
 * 
 * @author Máximo Sojo maxsojo13@gmail.com <maxtoan at atechnologies>
 */
class TabsExtension extends \Twig_Extension 
{
    use \Atechnologies\ToolsBundle\DependencyInjection\ContainerAwareTrait;
	
    /**
     * getFunctions
     * @author Máximo Sojo maxsojo13@gmail.com <maxtoan at atechnologies>
     * @return array
     */
    public function getFunctions()
    {
        return [            
            new \Twig_SimpleFunction('render_tabs', array($this, 'renderTabs'),array('is_safe' => ['html'])),
        ];
    }
    
    /**
     * Render base tabs
     * @author Máximo Sojo maxsojo13@gmail.com <maxtoan at atechnologies>
     * @param  \Atechnologies\ToolsBundle\Model\Core\Tab\Tab
     * @param  array
     * @return [type]
     */
    public function renderTabs(\Atechnologies\ToolsBundle\Model\Core\Tab\Tab $tab,array $parameters = []) 
    {
        $parameters["tab"] = $tab;
        return $this->container->get('templating')->render("AtechnologiesToolsBundle:tab:tabs.html.twig", 
            $parameters
        );
    }

    /**
     * getName
     * @author Máximo Sojo maxsojo13@gmail.com <maxtoan at atechnologies>
     * @return name
     */
    public function getName()
    {
        return 'atechnologies_tabs_extension';
    }
}