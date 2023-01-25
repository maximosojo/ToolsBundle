<?php

/*
 * This file is part of the Maximosojo Tools package.
 * 
 * (c) https://maximosojo.github.io/tools-bundle
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maximosojo\ToolsBundle\Model;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Maximosojo\ToolsBundle\DependencyInjection\ContainerAwareTrait;
use Maximosojo\ToolsBundle\Traits\Component\TranslatorTrait;
use Maximosojo\ToolsBundle\Component\Routing\Generator\UrlGeneratorTrait;

/**
 * Abstract menu builder.
 *
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
abstract class MenuBuilder implements \Symfony\Component\DependencyInjection\ContainerAwareInterface
{
    use ContainerAwareTrait;
    use TranslatorTrait;
    use UrlGeneratorTrait;

    /**
     * Menu factory.
     *
     * @var FactoryInterface
     */
    protected $factory;

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
     * Checks if the attributes are granted against the current authentication token and optionally supplied subject.
     *
     * @param mixed $attributes The attributes
     * @param mixed $subject    The subject
     *
     * @return bool
     *
     * @throws \LogicException
     *
     * @final since version 3.4
     */
    protected function isGranted($attributes, $subject = null) 
    {
        if (!$this->container->has('security.authorization_checker')) {
            throw new \LogicException('The SecurityBundle is not registered in your application. Try running "composer require symfony/security-bundle".');
        }

        return $this->container->get('security.authorization_checker')->isGranted($attributes, $subject);
    }
}
