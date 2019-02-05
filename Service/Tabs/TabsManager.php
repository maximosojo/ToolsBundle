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

use Symfony\Component\HttpFoundation\RequestStack;
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
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var Request
     */
    private $request;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
        $this->request = $this->requestStack->getCurrentRequest();        
    }

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
        $this->request->getSession()->set(Tab::ID_CURRENT_TAB,$this->tab->getId());

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
        $url = $tabContent->getOption("url")."&&content_id=".$tabContent->getId();
        $tabContent->setUrl($url);
        
        $this->resolveCurrentTab($tabContent);
    }

    /**
     * Registra tab selecionada
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @param  string $id
     */
    public function setCurrentTab($request)
    {
        $this->request->getSession()->set(Tab::ID_CURRENT_CONTENT,$request->get("content_id"));
    }

    /**
     * Resolver tabs activa
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @param  $tabContent
     * @return Tab
     */
    public function resolveCurrentTab($tabContent)
    {
        $request = $this->request;

        $currentTab = null;
        $tabId = $tabContent->getTab()->getId();
        $contentId = $tabContent->getId();
        $currentTab = $request->getSession()->get(Tab::ID_CURRENT_TAB);        
        
        $currentContent = null;
        if($request->getSession()->has(Tab::ID_CURRENT_CONTENT)){
            $currentContent = $request->getSession()->get(Tab::ID_CURRENT_CONTENT);
        }

        if (!$currentContent && $currentContent != $contentId) {
            $currentContent = $contentId;
            $request->getSession()->set(Tab::ID_CURRENT_CONTENT,$currentContent);
        }
        
        if ($currentTab == $tabId && $currentContent == $contentId) {
            $tabContent->setActive(true,true);
        }
    }
}
