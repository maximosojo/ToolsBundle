<?php

namespace Maximosojo\ToolsBundle\Service\Notifier;


/**
 * Capa de manejador
 *
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
interface TexterManagerInterface
{
    /**
     * Envío de mensaje
     */
    public function onSmsQueue($phoneNro = null, $messageText = null, array $options = array());
}
