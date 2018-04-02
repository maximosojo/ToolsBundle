<?php

/*
 * This file is part of the Atechnologies package.
 * 
 * (c) www.atechnologies.com.ve
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Atechnologies\ToolsBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Abstract menu builder.
 *
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
abstract class BaseMenuBuilder implements \Symfony\Component\DependencyInjection\ContainerAwareInterface
{
    /**
     * Container instance.
     *
     * @var Container
     */
    private $container;

    /**
     * Menu factory.
     *
     * @var FactoryInterface
     */
    protected $factory;

    /**
     * Request.
     *
     * @var Request
     */
    protected $request;

    /**
     * Constructor.
     *
     * @param FactoryInterface         $factory
     * @param SecurityContextInterface $securityContext
     * @param TranslatorInterface      $translator
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;        
    }

    /**
     * Sets the request the service
     *
     * @param Request $request
     */
    public function setRequest(Request $request = null)
    {
        $this->request = $request;
    }

    /**
     * Translate label.
     *
     * @param string $label
     * @param array  $parameters
     *
     * @return string
     */
    protected function trans($id,array $parameters = array(), $domain = 'messages')
    {
        return $this->container->get('translator')->trans($id, $parameters, $domain);
    }

    /**
     * This set container
     * @author Máximo Sojo maxsojo13@gmail.com <maxtoan at atechnologies>
     * @param  ContainerInterface|null
     */
    public function setContainer(ContainerInterface $container = null) 
    {
        $this->container = $container;
    }
}
