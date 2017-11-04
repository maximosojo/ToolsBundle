<?php

/*
 * This file is part of the Atechnologies package.
 * 
 * (c) www.atechnologies.com.ve
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Atechnologies\ToolsBundle\Model\Core\Tab;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Tab
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class Tab {

    private $id;
    private $name;
    private $icon;    
    private $options;
    
    /**
     * @var TabContent
     */
    private $tabsContent;
    
    public function __construct(array $options = []) {
        $this->tabsContent = [];
        $this->id = md5(\Atechnologies\ToolsBundle\Service\Util\AppUtil::getId());
        
        $this->setOptions($options);
    }
    
    /**
     * Carga de opciones
     * @author Máximo Sojo maxsojo13@gmail.com <maxtoan at atechnologies>
     * @param  array
     */
    public function setOptions(array $options = []) {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            "active_first" => true,
        ]);
        $this->options = $resolver->resolve($options);
        
        return $this;
    }
    
    public function getName() {
        return $this->name;
    }

    public function getIcon() {
        return $this->icon;
    }

    public function getTabsContent() {
        return $this->tabsContent;
    }

    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    public function setIcon($icon) {
        $this->icon = $icon;
        return $this;
    }
    
    public function getId() {
        return $this->id;
    }
    
    /**
     * Añade una tab
     * @param \Atechnologies\ToolsBundle\Model\Core\Tab\TabContent $tabContent
     * @return \Atechnologies\ToolsBundle\Model\Core\Tab\Tab
     * @throws \RuntimeException
     */
    public function addTabContent(TabContent $tabContent) 
    {
        $id = md5($tabContent->getName());
        if(isset($this->tabsContent[$id])){
            throw new \RuntimeException(sprintf("The tab content name '%s' is already added.",$tabContent->getName()));
        }
        if($this->options["active_first"] && count($this->tabsContent) == 0){
            $tabContent->setActive(true);
        }
        $this->tabsContent[$id] = $tabContent;
        $tabContent->setId($id);
        return $this;
    }
    
    /**
     * Convierte la tab a un array
     * @return type
     */
    public function toArray() {
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
}
