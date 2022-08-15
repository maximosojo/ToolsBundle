<?php

/*
 * This file is part of the GTI SOLUTIONS, C.A. - J409603954 package.
 * 
 * (c) www.gtisolutions.com.ve
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maximosojo\ToolsBundle\ORM\Filter;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

/**
 * Filtro para colocar Owner
 *
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
class OwnerFilter extends SQLFilter
{
    private $container;

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
        if (!isset($traits["Maximosojo\ToolsBundle\Traits\OwnerTrait"]) && !$targetEntity->reflClass->hasMethod("getOwner")) {
            return "";
        }
        
        $user = $this->getUser();
        if (is_null($user)) {
            return "";
        }

        $this->setParameter("owner",$user->getId());
        return $targetTableAlias.'.owner_id = ' . $this->getParameter('owner'); // getParameter applies quoting automatically
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

    public function setContainer($container)
    {        
       $this->container = $container;
    }

    /**
     * Get a user from the Security Context
     * @return mixed
     * @throws LogicException If SecurityBundle is not available
     * @see TokenInterface::getUser()
     */
    public function getUser()
    {
        if (!$this->container->has('security.token_storage')) {
            throw new LogicException('The SecurityBundle is not registered in your application.');
        }

        if (null === $token = $this->container->get('security.token_storage')->getToken()) {
            return;
        }

        if (!is_object($user = $token->getUser())) {
            return;
        }

        return $user;
    }
}