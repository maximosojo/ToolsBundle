<?php

/*
 * This file is part of the Máximo Sojo - maxtoan package.
 * 
 * (c) https://maximosojo.github.io/
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maxtoan\ToolsBundle\Component\FOS\RestBundle\View;

use FOS\RestBundle\View\View;

/**
 * FOSRestView trait.
 *
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
trait FOSRestViewTrait
{
    /**
     * Creates a view.
     *
     * Convenience method to allow for a fluent interface.
     *
     * @param mixed $data
     * @param int   $statusCode
     * @param array $headers
     *
     * @return \Maxtoan\ToolsBundle\Component\FOS\RestBundle\View\View
     */
    protected function view($data = null, $statusCode = null, array $headers = array())
    {
        return View::create($data, $statusCode, $headers);
    }
}