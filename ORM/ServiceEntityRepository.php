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

use Doctrine\Persistence\ManagerRegistry;
use Maximosojo\ToolsBundle\ORM\EntityRepositoryTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository as MasterServiceEntityRepository;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Service Entity Repository
 *
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
class ServiceEntityRepository extends MasterServiceEntityRepository
{
    protected $requestStack;

    /**
     * @required
     */
    public function setRequestStack(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    use EntityRepositoryTrait;

    /**
     * @return QueryBuilder
     */
    protected function getQueryBuilder()
    {
        return $this->createQueryBuilder($this->getAlias());
    }

    public function getAlias()
    {
        return "e";
    }
}
