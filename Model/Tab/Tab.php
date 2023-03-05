<?php

/*
 * This file is part of the Maximosojo Tools package.
 * 
 * (c) https://maximosojo.github.io/tools-bundle
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maximosojo\ToolsBundle\Model\Tab;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\HttpFoundation\Request;
use RuntimeException;

/**
 * Tab
 *
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
class Tab
{
    const NAME_CURRENT_TAB = "_tabst82a";
    const LAST_CURRENT_TABS = "_tabs471";
    const SORT_PROPERTY = "_f3fe5b35e8ec8";
    const SORT_ORDER = "_e7c860cda";

    private $id;

    private $name;

    private $icon;

    private $options;

    /**
     * Url base para todas las tabs content que no tengan url
     * @var string
     */
    private $rootUrl;

    /**
     * @var Request
     */
    private $request;

    /**
     *
     * @var TabContent
     */
    private $tabsContent;

    /**
     * @var TabContent
     */
    private $currentTabContent;
    
    /**
     * Parametros de la tab
     * @var array 
     */
    private $parameters = [];

    /**
     * Parametros globables puede ser un callable que retorne un array o un array
     * @var mixed 
     */
    private $viewParameters;

    public function __construct(array $options = [])
    {
        $this->tabsContent = [];
        $this->id = null;
        $this->setOptions($options);
    }

    public function setOptions(array $options = [])
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            "active_first" => true,
            "object_id" => null,
            "default_template" => null,
        ]);
        $this->options = $resolver->resolve($options);

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getIcon()
    {
        return $this->icon;
    }

    public function getTabsContent()
    {
        $this->resolveCurrentTab();
        return $this->tabsContent;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function setIcon($icon)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * getId
     * @return id
     */
    public function getId() 
    {
        if ($this->id === null) {
            $id = "";
            foreach ($this->tabsContent as $tabContent) {
                $id .= $tabContent->getId();
            }
            $this->id = "tab_" .md5($id."_".$this->options["object_id"]);
        }
        return $this->id;
    }

    /**
     * Añade una tab
     * @param \App\Model\Core\Tab\TabContent $tabContent
     * @return \App\Model\Core\Tab\Tab
     * @throws \RuntimeException
     */
    public function addTabContent(TabContent $tabContent) 
    {
        $id = "tc_".md5($tabContent->getTitle());
        if (isset($this->tabsContent[$id])) {
            throw new \RuntimeException(sprintf("The tab content name '%s' is already added.", $tabContent->getTitle()));
        }
        $this->tabsContent[$id] = $tabContent;
        $tabContent->setId($id);
        $tabContent->setTabRoot($this);

        return $this;
    }

    /**
     * @return TabContent
     */
    public function resolveCurrentTab()
    {
        if($this->currentTabContent){
            return $this->currentTabContent;
        }

        $current = null;
        $request = $this->request;
        $isNavitaging = false;
        // if($request->getSession()->has(Tab::NAME_CURRENT_TAB)){
        //     $current = $request->getSession()->get(Tab::NAME_CURRENT_TAB);
        // }
        
        // $currentSession = $current;
        // if($request->query->has(Tab::NAME_CURRENT_TAB)){//El valor del request remplaza el de la cache
        //     $current = $request->query->get(Tab::NAME_CURRENT_TAB);
        //     $request->getSession()->set(Tab::NAME_CURRENT_TAB,$current);
        // }

        // $lasts = $request->getSession()->get(self::LAST_CURRENT_TABS,[]);

        // $id = $this->getId();

        // //Si el id de la tab actual es el mismo significa que esta navegando
        // $currentId = null;
        $currentTabId = null;
        $currentTabId = $this->request->get("tab-selected");
        // $currentTab = is_null($tabSelected)?reset($this->tabsContent):$this->tabsContent[$tabSelected];

        // if(!empty($currentSession)){
        //     $currentSessionExp = explode("#",$currentSession);
        //     if($currentSessionExp[0] !== $id){
        //         $isNavitaging = true;
        //             foreach ($lasts as $key => $value) {//Buscar el ultimo historial
        //                 if(strpos($value, $id) !== false){//Tiene historial
        //                     //Si el nuevo movimiento es diferente
        //                     $current = $value;
        //                     break;
        //                 }
        //             }
        //     }
        // }
        // if($request->get("isInit") === "1" && $request->query->has(Tab::NAME_CURRENT_TAB)){//Si ya la tab se inicializo, entonces se renderiza lo que venga en el request
        //     $current = $request->query->get(Tab::NAME_CURRENT_TAB);
        // }
        // $exp = explode("#", $current);
        // if (count($exp) == 2) {
        //     $currentId = $exp[0];
        //     $currentTabId = $exp[1];
        // }
        
        $activeTab = null;
        foreach ($this->tabsContent as $tabContent) {
            if ($tabContent->getId() === $currentTabId) {
                $activeTab = $tabContent;
                break;
            }
        }
        // if (!empty($current)) {
        //     foreach ($this->tabsContent as $tabContent) {
        //         if ($tabContent->getId() === $currentTabId) {
        //             $activeTab = $tabContent;
        //             break;
        //         }
        //     }

        //     //Guardar en las ultimas 10 navegaciones
        //     if($activeTab !== null){
        //         foreach ($lasts as $key => $value) {
        //             if(strpos($value, $id) !== false){//Eliminar repetidos
        //                 unset($lasts[$key]);
        //             }
        //         }
        //         array_unshift($lasts,$id."#".$activeTab->getId());
        //         $lasts = array_values($lasts);
        //         while(count($lasts) > 10){//Solo guardar los ultimos 10 historiales
        //             unset($lasts[count($lasts) - 1]);
        //         }
        //         $request->getSession()->set(self::LAST_CURRENT_TABS,$lasts);
        //     }
        // }

        if ($activeTab === null) {
            $activeTab = reset($this->tabsContent);
        }

        if($this->tabsContent !== null){
            $activeTab->setActive(true);
        }

        $this->currentTabContent = $activeTab;

        return $activeTab;
    }
    
    public function getCurrentTabContent()
    {
        return $this->currentTabContent;
    }
    
    public function getTemplate($default)
    {
        $template = $default;
        if($this->request->isXmlHttpRequest() && $this->request->get("ajax")){
            $current = $this->resolveCurrentTab();
            if($current !== null){
                $template = $current->getOption("template");
            }
        }
        return $template;
    }
    
    /**
     * Convierte la tab a un array
     * @return type
     */
    public function toArray() 
    {
        $data = [
            "id" => $this->id,
            "name" => $this->name,
            "tabsContent" => [],
        ];
        
        foreach ($this->tabsContent as $tabContent) {
            $data["tabsContent"][] = $tabContent->toArray();
        }
        
        return $data;
    }

    public function setRequest(Request $request)
    {
        $this->request = $request;
        return $this;
    }
    
    public function getRootUrl()
    {
        return $this->rootUrl;
    }

    public function setRootUrl($rootUrl)
    {
        $this->rootUrl = $rootUrl;
        return $this;
    }
    
    /**
     * Busca un parametro
     * @param type $key
     * @return type
     */
    public function getParameter($key)
    {
        if(!isset($this->parameters[$key])){
            throw new RuntimeException(sprintf("The parameter '%s' is not exists. Available are %s",$key, implode(",", array_keys($this->parameters))));
        }
        return $this->parameters[$key];
    }

    public function setParameters(array $parameters = [])
    {
        $this->parameters = $parameters;
        return $this;
    }
    
    /**
     * Busca una opcion
     * @param type $name
     * @return type
     */
    public function getOption($name) 
    {
        $value = $this->options[$name];
        return $value;
    }
    
    public function getViewParameters()
    {
        return $this->viewParameters;
    }

    public function setViewParameters($viewParameters)
    {
        if(!is_callable($viewParameters) && !is_array($viewParameters)){
            throw new RuntimeException(sprintf("viewParameters debe ser array o callable pero se dio %s", gettype($viewParameters)));
        }
        $this->viewParameters = $viewParameters;
        return $this;
    }


    /**
     * @return TabContent
     */
    public function getCurrentTab()
    {
        $tabSelected = $this->request->get("tab-selected");
        $currentTab = is_null($tabSelected)?reset($this->tabsContent):$this->tabsContent[$tabSelected];
        return $currentTab;
    }
}
