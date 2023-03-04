<?php

/*
 * This file is part of the BtoB4Rewards package.
 * 
 * (c) www.btob4rewards.com
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maximosojo\ToolsBundle\Service\OptionManager\Adapter;

use Maximosojo\ToolsBundle\Service\OptionManager\OptionInterface;

/**
 * Adaptador de doctrine2
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class DoctrineORMAdapter implements OptionAdapterInterface
{
    /**
     * Manejador de entidades
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;
    
    protected $className;
    
    public function __construct(\Doctrine\ORM\EntityManager $em,$className)
    {
        $this->em = $em;
        $this->className = $className;
    }
    
    public function createNew()
    {
        return new $this->className();
    }

    public function find($key)
    {
        return $this->em->getRepository($this->className)->findOneBy([
            "key" => $key,
        ]);
    }

    public function findAll()
    {
        $r = [];
        try {
            $r = $this->em->getRepository($this->className)->findAll();
        } catch (\Doctrine\DBAL\Exception\TableNotFoundException $ex) {
            //Validar si la tabla no existe, se devuelve los resultados vacios.
            if($ex->getErrorCode() !== 1146){
                throw $ex;
            }
        }
        return $r;
    }

    public function persist(OptionInterface $configuration)
    {
        $this->em->persist($configuration);
        return true;
    }

    public function flush()
    {
        $this->em->flush();
        return true;
    }
}
