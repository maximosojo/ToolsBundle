<?php

/*
 * This file is part of the Maxtoan Tools package.
 * 
 * (c) https://maximosojo.github.io/tools-bundle
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maxtoan\ToolsBundle\Component\Doctrine\Migrations;

use Doctrine\Migrations\AbstractMigration as BaseAbstractMigration;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Maxtoan\ToolsBundle\DependencyInjection\DoctrineTrait;
use Maxtoan\ToolsBundle\DependencyInjection\ContainerAwareTrait;

/**
 * Base de migracion
 *
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
abstract class AbstractMigration extends BaseAbstractMigration implements ContainerAwareInterface
{
    use ContainerAwareTrait;
    use DoctrineTrait;
}
