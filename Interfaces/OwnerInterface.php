<?php

/*
 * This file is part of the Maxtoan Tools package.
 * 
 * (c) https://maxtoan.github.io/tools-bundle
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maxtoan\ToolsBundle\Interfaces;

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
