<?php

namespace Maxtoan\ToolsBundle\Service\Sms\Transports;

use Maxtoan\ToolsBundle\Model\Sms\ModelMessage;
use Maxtoan\ToolsBundle\Service\Sms\BaseTransport;

/**
 * Mensajes dummy
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class DummyTransport extends BaseTransport
{
    public function send(ModelMessage $message)
    {
        return true;
    }

    public function getPriority()
    {
        return 1000;
    }

    public function isEnabled()
    {
        return true;
    }

    public static function getName()
    {
        return "dummy";
    }
}
