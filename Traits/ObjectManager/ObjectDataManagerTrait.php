<?php

/*
 * This file is part of the Maxtoan Tools package.
 * 
 * (c) https://maximosojo.github.io/tools-bundle
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maxtoan\ToolsBundle\Traits\ObjectManager;

use Maxtoan\ToolsBundle\Service\ObjectManager\ObjectDataManagerInterface;

/**
 * Trait de manejador de documentos
 *
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
trait ObjectDataManagerTrait
{
    protected $objectDataManager;

    /**
     * @required
     */
    public function setObjectDataManager(ObjectDataManagerInterface $objectDataManager)
    {
        $this->objectDataManager = $objectDataManager;
    }
}