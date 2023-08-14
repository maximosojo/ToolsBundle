<?php

/*
 * This file is part of the Máximo Sojo - maximosojo package.
 * 
 * (c) https://maximosojo.github.io/
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maximosojo\ToolsBundle\DependencyInjection;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Doctrine trait.
 *
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
trait DoctrineTrait
{
    /**
     * Bandera para permitir una transaccion simultanea
     * @var type 
     */
    private $isBeginTransaction = false;

    private $entityManager;

    /**
     * entityManager
     * @required
     */
    public function setEntityManager(\Doctrine\ORM\EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    protected function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * Shortcut to return the Doctrine Registry service.
     *
     * @return \Doctrine\Bundle\DoctrineBundle\Registry
     *
     * @throws \LogicException If DoctrineBundle is not available
     */
    protected function getDoctrine(): ManagerRegistry
    {
        if (!$this->container->has('doctrine')) {
            throw new \LogicException('The DoctrineBundle is not registered in your application.');
        }

        return $this->container->get('doctrine');
    }

    /**
     * Retorna el repositorio principal
     * @return \Maximosojo\ToolsBundle\Model\Base\EntityRepository
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
     * @throws \Maximosojo\ToolsBundle\Exception\NotImplementedException
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
     * @return Boolean
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
     * @return Boolean
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
     * @return Boolean
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
    protected function doPersist($entity, $andFlush = true)
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
    protected function doRemove($entity = null, $andFlush = true)
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
    protected function doFlush()
    {
        $em = $this->getEntityManager();

        try {
            $em->flush();            
        } catch (Exception $e) {
            $em->rollBack();
        }
    }

    /**
     * Contruye un queryBuilder de una clase
     * @param type $class
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function findQueryBuilderForClass($class, array $method = [])
    {
        $em = $this->getEntityManager();        
        $alias = "c";
        $qb = $em->createQueryBuilder()
                ->select($alias)
                ->from($class, $alias);

        foreach ($method as $key => $value) {
            $qb
                ->andWhere(sprintf("%s.%s = :%s",$alias,$key,$key))
                ->setParameter($key, $value)
            ;
        }

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * 
     * @param type $class
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function findQueryBuilder($class)
    {
        $em = $this->getEntityManager();        
        $alias = "c";
        $qb = $em->createQueryBuilder()
                ->select($alias)
                ->from($class, $alias);

        return $qb;
    }

    protected function doClear()
    {
        $em = $this->getEntityManager();
        $em->clear();
    }

    /**
     * Repara el error:
     * A new entity was found through the relationship 'App\Entity\Example#property' that was not configured to cascade persist operations for entity: Data entity example.. To solve this issue: Either explicitly call EntityManager#persist() on this unknown entity or configure cascade persist  this association in the mapping for example ManyToOne(..,cascade={"persist"})
     * Importante: Usar con la variable para que funcione "$object = $this->doMerge($object);"
     * @param type $entity
     * @return type
     */
    protected function doMerge($entity)
    {
        $em = $this->getEntityManager();
        $entity = $em->merge($entity);
        return $entity;
    }
}