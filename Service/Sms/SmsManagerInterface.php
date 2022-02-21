<?php

namespace Maxtoan\ToolsBundle\Service\Sms;


/**
 * Capa de manejador
 *
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
interface SmsManagerInterface
{
    /**
     * Envío de mensaje
     */
    public function send($phoneNro = null, $messageText = null, array $options = array());
}
