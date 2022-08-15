<?php

namespace Maximosojo\ToolsBundle\Service\Sms;

use Psr\Log\LoggerAwareInterface;
use Maximosojo\ToolsBundle\Model\Sms\ModelMessage;

/**
 * Capa de transporte
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
interface TransportInterface extends LoggerAwareInterface
{
    /**
     * Envío de mensaje
     */
    public function send(ModelMessage $message);

    /**
     * Prioridad: Mientras mas alta mas prioridad tiene
     */
    public function getPriority();

    /**
     * Esta activo
     *
     * @return Boolean
     */
    public function isEnabled();

    /**
     * Nombre para manejo
     *
     * @return  String
     */
    public static function getName();
}
