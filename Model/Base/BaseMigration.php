<?php

/*
 * This file is part of the Atechnologies package.
 * 
 * (c) www.atechnologies.com.ve
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Atechnologies\ToolsBundle\Model\Base;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Base para las migraciones
 *
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
abstract class BaseMigration extends AbstractMigration implements ContainerAwareInterface
{
    use \Atechnologies\ToolsBundle\DependencyInjection\ContainerAwareTrait;
    use \Atechnologies\ToolsBundle\DependencyInjection\DoctrineTrait;    
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
