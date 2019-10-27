<?php

/*
 * This file is part of the Maxtoan Tools package.
 * 
 * (c) https://maxtoan.github.io/tools-bundle
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maxtoan\ToolsBundle\Controller;

/**
 * Controlador con funciones base
 *  
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
trait ControllerTrait
{
    /**
     * $jsonResponse
     * @var JsonResponse
     */
    private $jsonResponse;

    /**
     * Json reponse
     * @param type $data
     * @param type $status
     * @param type $headers
     * @return \Maxtoan\ToolsBundle\Component\HttpFoundation\JsonResponse
     */
    protected function myJsonResponse($data = null, $status = 200, $headers = array()) 
    {
        if (!$this->jsonResponse) {
            $this->jsonResponse = new \Maxtoan\ToolsBundle\Component\HttpFoundation\JsonResponse($data, $status, $headers);
        }

        return $this->jsonResponse;
    }

    /**
     * Set flash in json reponse
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @param  String
     * @param  String
     * @param  array
     * @param  integer
     */
    public function setData($data)
    {
        $response = $this->myJsonResponse();
        $response->setData($data);

        return $response;
    }
    
    /**
     * Set flash in json reponse
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @param  String
     * @param  String
     * @param  array
     * @param  integer
     */
    public function setFlashJson($typeFlash, $value, $parameters = array(), $status = 200)
    {
        $response = $this->myJsonResponse(null,$status);
        $response->setFlash($typeFlash, $this->trans($value, $parameters, "flashes"));

        return $response;
    }

    /**
     * Json redirect
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @param  String
     */
    public function setJsonRedirect($url)
    {
        $response = $this->myJsonResponse();
        $response->setRedirect($url);

        return $response;
    }

    /**
     * Json force redirect
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @param  String
     */
    public function setForceRedirect($url)
    {
        $response = $this->myJsonResponse();
        $response->setForceRedirect($url);

        return $response;
    }

    /**
     * Json redirect
     * @author Máximo Sojo <maxsojo13@gmail.com>
     */
    public function setForceReload()
    {
        $response = $this->myJsonResponse();
        $response->setForceReload();

        return $response;
    }

    /**
     * Refresh paginator
     * @author Máximo Sojo <maxsojo13@gmail.com>
     */
    public function setRefreshPaginator($key = null)
    {
        $response = $this->myJsonResponse();
        $response->setRefreshPaginator($key);

        return $response;
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