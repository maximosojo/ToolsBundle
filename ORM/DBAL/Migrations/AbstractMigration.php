<?php

/*
 * This file is part of the Maxtoan Tools package.
 * 
 * (c) https://maxtoan.github.io/tools-bundle
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maxtoan\ToolsBundle\ORM\DBAL\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration as BaseAbstractMigration;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Base para las migraciones
 *
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
abstract class AbstractMigration extends BaseAbstractMigration implements ContainerAwareInterface
{
    use \Maxtoan\ToolsBundle\DependencyInjection\ContainerAwareTrait;
    use \Maxtoan\ToolsBundle\DependencyInjection\DoctrineTrait;    
    
    /**
     * Shortcut to return the Doctrine Registry service.
     * @return Registry
     * @throws LogicException If DoctrineBundle is not available
     */
    public function getDoctrine() 
    {
        if (!$this->container->has('doctrine')) {
            throw new LogicException('The DoctrineBundle is not registered in your application.');
        }

        return $this->container->get('doctrine');
    }
}
