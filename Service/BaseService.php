<?php

/*
 * This file is part of the Maximosojo Tools package.
 * 
 * (c) https://maximosojo.github.io/tools-bundle
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maximosojo\ToolsBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Maximosojo\ToolsBundle\DependencyInjection\ContainerAwareTrait;
use Maximosojo\ToolsBundle\DependencyInjection\DoctrineTrait;
use Maximosojo\ToolsBundle\Traits\Component\ErrorTrait;

/**
 * Servicio base con implementación de funciones genericas compartidas
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
class BaseService implements ContainerAwareInterface 
{
    use ContainerAwareTrait;
    use DoctrineTrait;
    use ErrorTrait;
    
    /**
     * Base de archivos de comandos de impresoras
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @return Base url
     */
    public function getBaseUrl()
    {
        return $this->container->getParameter('kernel.project_dir');
    }

    /**
     * Get a user from the Security Context
     * @return mixed
     * @throws LogicException If SecurityBundle is not available
     * @see TokenInterface::getUser()
     */
    public function getUser()
    {
        if (!$this->container->has('security.token_storage')) {
            throw new LogicException('The SecurityBundle is not registered in your application.');
        }

        if (null === $token = $this->container->get('security.token_storage')->getToken()) {
            return;
        }

        if (!is_object($user = $token->getUser())) {
            return;
        }

        return $user;
    }

    /**
     * Genera una url
     * @param type $route
     * @param array $parameters
     * @return type
     */
    protected function generateUrl($route, $parameters = array(), $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH)
    {
        return $this->container->get('router')->generate($route, $parameters, $referenceType);
    }
}
