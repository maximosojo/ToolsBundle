<?php

/*
 * This file is part of the GTI SOLUTIONS, C.A. - J409603954 package.
 * 
 * (c) www.gtisolutions.com.ve
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maxtoan\ToolsBundle\ORM\Filter\Enableable;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

/**
 * Filtro para colocar enabled = true en todos los que implementen
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class EnableableFilter extends SQLFilter
{
    /**
     * @var string[bool]
     */
    protected $disabled = array();
    
    /**
     * @param  ClassMetadata $targetEntity
     * @param  $targetTableAlias
     */
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
        $class = $targetEntity->getName();
        if (array_key_exists($class, $this->disabled) && $this->disabled[$class] === true) {
            return '';
        } elseif (array_key_exists($targetEntity->rootEntityName, $this->disabled) && $this->disabled[$targetEntity->rootEntityName] === true) {
            return '';
        }
        
        $traits = $targetEntity->reflClass->getTraits();
        // Check if the entity implements the LocalAware interface
        if (!isset($traits["Maxtoan\ToolsBundle\Traits\EnableableTrait"]) && !$targetEntity->reflClass->hasMethod("getEnabled")) {
            return "";
        }

        $this->setParameter("enabled",true);
        return $targetTableAlias.'.enabled = ' . $this->getParameter('enabled'); // getParameter applies quoting automatically
    }
    
    /**
     * @param string $class
     */
    public function disableForEntity($class)
    {
        $this->disabled[$class] = true;
    }

    /**
     * @param string $class
     */
    public function enableForEntity($class)
    {
        $this->disabled[$class] = false;
    }
}