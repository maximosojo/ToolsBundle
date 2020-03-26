<?php

/*
 * This file is part of the Maxtoan Tools package.
 * 
 * (c) https://maximosojo.github.io/tools-bundle
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maxtoan\ToolsBundle\Model\Tab;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Maxtoan\ToolsBundle\Model\Tab\Tab;

/**
 * Contenido de tab
 * 
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class TabContent 
{
    /**
     * $id
     * @var string
     */
    private $id;

    /**
     * $url
     * @var string
     */
    private $url;

    /**
     * $icon
     * @var string
     */
    private $icon;

    /**
     * $name
     * @var string
     */
    private $name;

    /**
     * $order
     * @var string
     */
    private $order;

    /**
     * $options
     * @var array
     */
    private $options;

    /**
     * $tab
     * @var object
     */
    private $tab;

    /**
     * $active
     * @var boolean
     */
    private $active = false;
    
    public function __construct(array $options = []) 
    {
        $this->setOptions($options);
    }
    
    /**
     * Opciones de la tab
     * @param array $options
     * @return \Maxtoan\ToolsBundle\Model\Tab\TabContent
     */
    public function setOptions(array $options = []) 
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            "add_content_div" => true,
        ]);
        $resolver->setRequired(["url"]);
        $this->options = $resolver->resolve($options);
        
        return $this;
    }
    
    /**
     * Busca una opcion
     * @param type $name
     * @return type
     */
    public function getOption($name) 
    {
        return $this->options[$name];
    }
    
    /**
     * getUrl
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @return url
     */
    public function getUrl() 
    {
        return $this->url;
    }

    /**
     * getName
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @return [type]
     */
    public function getName() 
    {
        return $this->name;
    }

    /**
     * getOrder
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @return order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * setUrl
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @param  url
     */
    public function setUrl($url) 
    {
        $this->url = $url;
        
        return $this;
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
     * setOrder
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @param  order
     */
    public function setOrder($order) 
    {
        $this->order = $order;

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
     * @param  id
     */
    public function setId($id) 
    {
        $this->id = $id;

        return $this;
    }
    
    /**
     * getActive
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @return active
     */
    public function getActive() 
    {
        return $this->active;
    }
    
    /**
     * setActive
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @param  boolean
     */
    public function setActive($active,$recursive = false) 
    {
        if ($recursive) {
            foreach ($this->tab->getTabsContent() as $tab) {
                $tab->setActive(false);
            }
        }

        $this->active = $active;

        return $this;
    }
    
    /**
     * geticon
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @return icon
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * seticon
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @param  icon
     */
    public function setIcon($icon) 
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * getTab
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @return tab
     */
    public function getTab()
    {
        return $this->tab;
    }

    /**
     * settab
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @param  tab
     */
    public function setTab(Tab $tab) 
    {
        $this->tab = $tab;

        return $this;
    }

    /**
     * Representacion de la tab en arary
     * @return array
     */
    public function toArray() 
    {
        $data = [
            "id" => $this->id,
            "name" => $this->name,
            "url" => $this->url,
            "icon" => $this->icon,
            "active" => $this->active,
            "options" => $this->options,
        ];

        return $data;
    }
}
