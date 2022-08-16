<?php

/*
 * This file is part of the Maximosojo Tools package.
 * 
 * (c) https://maximosojo.github.io/tools-bundle
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maximosojo\ToolsBundle\Controller;

use Maximosojo\ToolsBundle\Component\HttpFoundation\JsonResponse;

/**
 * Controlador con funciones base
 *  
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
trait ControllerTrait
{
    /**
     * Json reponse
     * @param type $data
     * @param type $status
     * @param type $headers
     * @return \Maximosojo\ToolsBundle\Component\HttpFoundation\JsonResponse
     */
    protected function newJsonResponse($data = null, $status = 200, $headers = array()) 
    {
        return new JsonResponse($data, $status, $headers);
    }

    /**
     * Habilitar filtro
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @return Filter
     */
    public function filterEnabled($filter)
    {
        if (!$filter) {
            return;
        }
        $em = $this->getDoctrine()->getManager();
        $em->getFilters()->enable($filter);
    }

    /**
     * Deshabilidar filtro
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @return Filter
     */
    public function filterDisabled($filter)
    {
        if (!$filter) {
            return;
        }
        $em = $this->getDoctrine()->getManager();
        $em->getFilters()->disable($filter);
    }   
}