<?php

/*
 * This file is part of the Atechnologies package.
 * 
 * (c) www.atechnologies.com.ve
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Atechnologies\ToolsBundle\Controller;

/**
 * Controlador con funciones base
 *  
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
trait ControllerTrait
{
    /**
     * $jsonResponse
     */
    private $jsonResponse;

    /**
     * Json reponse
     * @param type $data
     * @param type $status
     * @param type $headers
     * @return \Atechnologies\ToolsBundle\Custom\HttpFoundation\MyJsonResponse
     */
    protected function myJsonResponse($data = null, $status = 200, $headers = array()) 
    {
        if (!$this->jsonResponse) {
            $this->jsonResponse = new \Atechnologies\ToolsBundle\Custom\HttpFoundation\MyJsonResponse($data, $status, $headers);
        }

        return $this->jsonResponse;
    }

    /**
     * Set flash in json reponse
     * @author Máximo Sojo <maxsojo13@gmail.com>
     */
    public function setFlashJson($typeFlash, $value, $parameters = array(), $status = 200)
    {
        $response = $this->myJsonResponse(null,$status);
        $response->setFlash($typeFlash, $this->trans($value, $parameters, "flashes"));

        return $response;
    }

    /**
     * Json redirect
     * @author Máximo Sojo maxsojo13@gmail.com <maxtoan at atechnologies>
     * @param  [type]
     * @return [type]
     */
    public function setJsonRedirect($url)
    {
        $response = $this->myJsonResponse();
        $response->setRedirect($url);

        return $response;
    }

    /**
     * Json redirect
     * @author Máximo Sojo maxsojo13@gmail.com <maxtoan at atechnologies>
     * @param  [type]
     * @return [type]
     */
    public function setForceReload()
    {
        $response = $this->myJsonResponse();
        $response->setForceReload();

        return $response;
    }

	/**
     * Traducción
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @param  String
     * @param  array
     * @param  String
     * @return String
     */
    protected function trans($id,array $parameters = array(), $domain = "")
    {
        return $this->container->get('translator')->trans($id, $parameters, $domain);
    }
}