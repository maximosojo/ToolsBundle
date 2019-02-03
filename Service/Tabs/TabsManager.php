<?php

/*
 * This file is part of the Grupo Farmaingenio C.A. - J406111090 package.
 * 
 * (c) www.tconin.com
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Atechnologies\ToolsBundle\Service\Tabs;

use Atechnologies\ToolsBundle\Model\Core\Tab\Tab;
use Atechnologies\ToolsBundle\Model\Core\Tab\TabContent;

/**
 * Servicio generador de tabs (atechnologies.manager.tabs)
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
class TabsManager extends \Atechnologies\ToolsBundle\Service\BaseService 
{
    /**
     * $tab
     * @var Tab
     */
    private $tab;

    /**
     * Instancia nueva tabs
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @param  Class
     * @return Tab
     */
    public function createNew($class = null)
    {
        $this->tab = new Tab([]);
        $this->tab->setId(get_class($class).$class->getId());

        return $this->tab;
    }

    /**
     * Instancia nuevo contenido de tab
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @param  array
     * @return TabContent
     */
    public function createNewContent(array $options = array())
    {
        $tabContent = new TabContent($options);
        $tabContent->setTab($this->tab);

        return $tabContent;
    }

    /**
     * Registra contenido en tab
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @param  TabContent
     */
    public function addTabContent(TabContent $tabContent)
    {
        $this->tab->addTabContent($tabContent);
    }
}
