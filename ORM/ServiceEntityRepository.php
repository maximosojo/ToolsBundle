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
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Maximosojo\ToolsBundle\ORM\EntityRepositoryTrait;
use Maximosojo\ToolsBundle\DependencyInjection\ContainerAwareTrait;

/**
 * Service Entity Repository
 *
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
class ServiceEntityRepository extends \Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository implements ContainerAwareInterface
{
    use ContainerAwareTrait;
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
