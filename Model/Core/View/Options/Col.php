<?php

namespace Maxtoan\ToolsBundle\Model\Core\View\Options;

/**
 * Col
 * 
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
class Col
{
    /**
     * @var array
     */
    private $properties;

    /**
     * Row constructor.
     */
    public function __construct()
    {
        $this->properties = [];
    }

    /**
     * @return properties
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @param properties $properties
     */
    public function setProperties($properties)
    {
        $this->properties = $properties;
        
        return $this;
    }

    /**
     * @param properties $properties
     */
    public function addProperty(Property $property)
    {
        $this->properties[] = $property;
        
        return $this;
    }
}
