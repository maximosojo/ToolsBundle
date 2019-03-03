<?php

/*
 * This file is part of the Maxtoan Tools package.
 * 
 * (c) https://maxtoan.github.io/tools-bundle
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maxtoan\ToolsBundle\Model\Tab;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Tab
 * 
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class Tab
{   
    /**
     * Current for tabs session
     * @author Máximo Sojo <maxsojo13@gmail.com>
     */
    const ID_CLASS = "_class658";
    const ID_CURRENT_TAB = "_tabst82a";
    const ID_CURRENT_CONTENT = "_contents471";

    /**
     * $id
     * @var string
     */
    private $id;

    /**
     * $name
     * @var string
     */
    private $name;

    /**
     * $icon
     * @var string
     */
    private $icon;

    /**
     * $options
     * @var array
     */
    private $options;
    
    /**
     * @var TabContent
     */
    private $tabsContent;
    
    public function __construct(array $options = []) 
    {
        $this->tabsContent = [];
        $this->id = md5(\Maxtoan\Common\Util\StringUtil::getId());
        $this->setOptions($options);
    }
    
    /**
     * Carga de opciones
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @param  array
     */
    public function setOptions(array $options = []) 
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            "active_first" => true,
        ]);

        $this->options = $resolver->resolve($options);
        
        return $this;
    }
    
    /**
     * getName
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @return name
     */
    public function getName() 
    {
        return $this->name;
    }

    /**
     * getIcon
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @return icon
     */
    public function getIcon() 
    {
        return $this->icon;
    }

    /**
     * getTabsContent
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @return tabsContent
     */
    public function getTabsContent() 
    {
        return $this->tabsContent;
    }

    /**
     * setName
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @param  name
     */
    public function setName($name) 
    {
        $this->name = $name;

        return $this;
    }

    /**
     * setIcon
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @param  icon
     */
    public function setIcon($icon) 
    {
        $this->icon = $icon;

        return $this;
    }
    
    /**
     * getId
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @return id
     */
    public function getId() 
    {
        return $this->id;
    }

    /**
     * setId
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @return id
     */
    public function setId($id)
    {
        $this->id = md5($id);
    }
    
    /**
     * Add tab
     * @param \Maxtoan\ToolsBundle\Model\Tab\TabContent $tabContent
     * @return \Maxtoan\ToolsBundle\Model\Tab\Tab
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
        $tabContent->setId($id.$tabContent->getTab()->getId());
        
        return $this;
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
}
