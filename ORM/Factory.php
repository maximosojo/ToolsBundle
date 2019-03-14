<?php

/*
 * This file is part of the TecnoCreaciones package.
 * 
 * (c) www.tecnocreaciones.com
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maxtoan\ToolsBundle\ORM;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Repository\RepositoryFactory;

/**
 * Contructor de repositorios, permite usar servicios como repositorios de las entidades
 *
 * @author Carlos Mendoza <inhack20@tecnocreaciones.com>
 */
class Factory implements RepositoryFactory
{
    /**
     * $ids
     * @var String
     */
    private $ids;

    /**
     * $container
     * @var ContainerInterface
     */
    private $container;

    /**
     * $default
     * @var String
     */
    private $default;
 
    public function __construct(array $ids, ContainerInterface $container, RepositoryFactory $default)
    {
        $this->ids = $ids;
        $this->container = $container;
        $this->default = $default;
    }
 
    public function getRepository(EntityManagerInterface $entityManager, $entityName)
    {
        if(preg_match('/'. \Doctrine\Common\Persistence\Proxy::MARKER .'/',$entityName)){
            $entityName = \Doctrine\Common\Util\ClassUtils::getRealClass($entityName);
        }
        if (isset($this->ids[$entityName])) {
            return $this->container->get($this->ids[$entityName]);
        }
        $repository = $this->default->getRepository($entityManager, $entityName);
        if($repository instanceof \Symfony\Component\DependencyInjection\ContainerAwareInterface){
            $repository->setContainer($this->container);
        }
        return $repository;
    }
}
