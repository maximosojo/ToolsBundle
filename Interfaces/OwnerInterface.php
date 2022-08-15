<?php

/*
 * This file is part of the Maximosojo Tools package.
 * 
 * (c) https://maximosojo.github.io/tools-bundle
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maximosojo\ToolsBundle\Interfaces;

use App\Entity\M\User;

/**
 * OwnerInterface
 * @author Máaximo Sojo <maxsojo13@gmail.com>
 */
interface OwnerInterface 
{
    public function setOwner(User $owner);

    public function getOwner();    
}
