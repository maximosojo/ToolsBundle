<?php

/*
 * This file is part of the Maximosojo Tools package.
 * 
 * (c) https://maximosojo.github.io/tools-bundle
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maximosojo\ToolsBundle\ORM;

use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Maximosojo\ToolsBundle\Model\Paginator\Paginator;
use Pagerfanta\Adapter\ArrayAdapter;

/**
 * EntityRepositoryTrait
 *
 * @author Máximo Sojo <maxsojo13@gmail.com> 
 */
trait EntityRepositoryTrait 
{
    /**
     * @param QueryBuilder $queryBuilder
     * @return Paginator
     */
    public function getPaginator(QueryBuilder $queryBuilder)
    {        
        $request = $this->requestStack->getCurrentRequest();
        $pagerfanta = new Paginator(new DoctrineORMAdapter($queryBuilder));
        $pagerfanta->setDefaultFormat($this->getFormatPaginator());
        // $pagerfanta->setRouter($this->router);
        if($request){
            $pagerfanta->setRequest($request);
        }
        return $pagerfanta;
    }

    /**
     * Retorna un paginador con valores escalares (Sin hidratar)
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder
     * @return \
     */
    public function getPaginatorScalar(\Doctrine\ORM\QueryBuilder $queryBuilder)
    {
        $request = $this->container->get('request_stack')->getCurrentRequest();        
        $pagerfanta = new Paginator(new ArrayAdapter($queryBuilder->getQuery()->getScalarResult()));
        $pagerfanta->setDefaultFormat($this->getFormatPaginator());
        //$pagerfanta->setContainer($this->container);
        $pagerfanta->setRequest($request);
        return $pagerfanta;
    }
    
    /**
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @return [type]
     */
    public function findAllPaginated()
    {
        return $this->getPaginator($this->getQueryBuilder());
    }
    
    /**
     * Carga de formato de respuesta
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @return [type]
     */
    public function getFormatPaginator()
    {
        return $this->container->getParameter('paginator_format_array');
    }
    
    /**
     * @param type $qb
     * @param type $criteria
     * @return \Maximosojo\ToolsBundle\Repository\BaseRepository
     */
    protected function createSearchQueryBuilder($qb, $criteria,array $orderBy = []) 
    {
        return new \Maximosojo\ToolsBundle\ORM\Query\SearchQueryBuilder($qb, $criteria, $this->getAlias(),$orderBy);
    }
    
    /**
     * @param QueryBuilder $queryBuilder
     *
     * @param array $criteria
     */
    protected function applyCriteria(QueryBuilder $queryBuilder, array $criteria = null)
    {
        if (null === $criteria) {
            return;
        }

        foreach ($criteria as $property => $value) {
            if (null === $value) {
                $queryBuilder
                    ->andWhere($queryBuilder->expr()->isNull($this->getPropertyName($property)));
            } elseif (!is_array($value)) {
                $queryBuilder
                    ->andWhere($queryBuilder->expr()->eq($this->getPropertyName($property), ':' . $property))
                    ->setParameter($property, $value);
            } else {
                $queryBuilder->andWhere($queryBuilder->expr()->in($this->getPropertyName($property), $value));
            }
        }
    }
    
    /**
     * 
     * @param array $criteria
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    protected function parseCriteria(array $criteria) 
    {
        return new \Doctrine\Common\Collections\ArrayCollection($criteria);
    }
    
    /**
     * @param QueryBuilder $queryBuilder
     *
     * @param array $sorting
     */
    protected function applySorting(QueryBuilder $queryBuilder, array $sorting = null)
    {
        if (null === $sorting) {
            return;
        }

        foreach ($sorting as $property => $order) {
            if (!empty($order)) {
                $queryBuilder->orderBy($this->getPropertyName($property), $order);
            }
        }
    }
    
    /**
     * @param string $name
     *
     * @return string
     */
    protected function getPropertyName($name)
    {
        if (false === strpos($name, '.')) {
            return $this->getAlias().'.'.$name;
        }

        return $name;
    }
}