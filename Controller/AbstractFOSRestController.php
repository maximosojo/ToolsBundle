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

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\Translation\TranslatorInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController as BaseAbstractFOSRestController;
use Maximosojo\ToolsBundle\DependencyInjection\DoctrineTrait;
use Maximosojo\ToolsBundle\Traits\Component\EventDispatcherTrait;
use Maximosojo\ToolsBundle\Traits\Component\FOSRestViewTrait;
use Maximosojo\ToolsBundle\Traits\Component\TranslatorTrait;

/**
 * Controlador base
 *
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
abstract class AbstractFOSRestController extends BaseAbstractFOSRestController
{
    use ControllerTrait;
    use DoctrineTrait;
    use EventDispatcherTrait;
    use FOSRestViewTrait;
    use TranslatorTrait;
    
    /**
     * Tipo error
     */
    public const TYPE_DANGER = "error";
    
    /**
     * Tipo éxito
     */
    public const TYPE_SUCCESS = "success";
    
    /**
     * Tipo alerta
     */
    public const TYPE_WARNING = "warning";
    
    /**
     * Tipo información
     */
    public const TYPE_INFO = "info";

    /**
     * Tipo depuración
     */
    public const TYPE_DEBUG = "debug";
    
}