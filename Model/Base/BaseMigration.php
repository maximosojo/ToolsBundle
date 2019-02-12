<?php

namespace Atechnologies\ToolsBundle\Model\Base;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Base para las migraciones
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
