<?php

/*
 * This file is part of the Máximo Sojo - maxtoan package.
 * 
 * (c) https://maxtoan.github.io/
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maxtoan\ToolsBundle\DependencyInjection;

use Symfony\Component\HttpFoundation\Request;

/**
 * Doctrine trait.
 *
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
trait DoctrineTrait
{
    /**
     * Entity Manager
     * @var Doctrine Entity Manager
     */
    private $em;

    /**
     * Bandera para permitir una transaccion simultanea
     * @var type 
     */
    private $isBeginTransaction = false;

    /**
     * Shortcut to return the Doctrine Registry service.
     *
     * @return \Doctrine\Bundle\DoctrineBundle\Registry
     *
     * @throws \LogicException If DoctrineBundle is not available
     */
    protected function getDoctrine()
    {
        if (!$this->container->has('doctrine')) {
            throw new \LogicException('The DoctrineBundle is not registered in your application.');
        }

        return $this->container->get('doctrine');
    }

    /**
     * Manegador de doctrine
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        if (!$this->em) {
            $this->em = $this->getDoctrine()->getManager();
        }

        return $this->em;
    }

    /**
     * Retorna el repositorio principal
     * @return \Maxtoan\ToolsBundle\Model\Base\EntityRepository
     */
    protected function getRepository($repository = null)
    {
        $em = $this->getEntityManager();
        
        if (!$repository) {
            $repository = $this->getClass();
        }

        return $em->getRepository($repository);
    }

    /**
     * Debe retornar la clase principal que se esta manejando
     * @throws \Maxtoan\ToolsBundle\Exception\NotImplementedException
     */
    protected function getClass()
    {
        throw new \Exception("Error class not found", 1);
    }

    /**
     * Crea una nueva instancia
     * @throws type
     */
    protected function createNew($class = null)
    {
        if (!$class) {
            $class = $this->getClass();
        }

        return new $class();
    }
    
    /**
     * Busca un elemento o lanza una exepcion de 404
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return type
     * @throws type
     */
    protected function findOr404(Request $request,$className = null,$property = "id") 
    {
        $id = $request->get($property);
        if(empty($id)){
            throw $this->createNotFoundException("The identifier can not be empty.");
        }

        $resource = $this->getRepository($className)->find($id);
        if(!$resource){
            throw $this->createNotFoundException();
        }
        
        return $resource;
    }

    /**
     * Inicia una transaccion en la base de datos
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @return Booleam
     */
    protected function managerBeginTransaction()
    {
        if($this->isBeginTransaction === true){
            throw new \LogicException("No puede iniciar la transaccion dos veces. Realize el commit de la anterior");
        }

        $this->getEntityManager()->getConnection()->beginTransaction();

        $this->isBeginTransaction = true;
    }

    /**
     * Realiza el commit de una transaccion
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @return Booleam
     */
    protected function managerCommit()
    {
        if($this->isBeginTransaction === false){
            throw new \LogicException("No hay ninguna transaccion iniciada, primero debe iniciarla.");
        }

        $em = $this->getEntityManager();
        $em->flush();
        $em->getConnection()->commit();
        
        $this->isBeginTransaction = false;
    }

    /**
     * Roll back si falla la transaccion
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @return Booleam
     */
    protected function managerRollback()
    {
        if($this->isBeginTransaction === false){
            //throw new \LogicException("No hay ninguna transaccion iniciada, primero debe iniciarla.");
            return;
        }

        $this->getEntityManager()->getConnection()->rollback();
        
        $this->isBeginTransaction = false;
    }
    
    /**
     * Guarda entidad
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @param  Entity
     * @param  boolean
     * @return Entity
     */
    protected function emSave($entity, $andFlush = true)
    {
        $em = $this->getEntityManager();
        
        try {
            $em->persist($entity);
            if($andFlush === true){
                $em->flush();
            }
        } catch (Exception $e) {
            $em->rollBack();
        }
    }

    /**
     * Remove object
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @param  Entity
     * @param  boolean
     * @return Entity
     */
    protected function emRemove($entity = null, $andFlush = true)
    {
        $em = $this->getEntityManager();

        try {
            if ($entity !== null) {
                $em->remove($entity);
            }
            if ($andFlush === true) {
                $em->flush();
            }            
        } catch (Exception $e) {
            $em->rollBack();
        }
    }
    
    /**
     * Flush  object
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @return [type]
     */
    protected function emFlush()
    {
        $em = $this->getEntityManager();

        try {
            $em->flush();            
        } catch (Exception $e) {
            $em->rollBack();
        }
    }
}