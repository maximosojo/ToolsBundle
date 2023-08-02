<?php

namespace Maximosojo\ToolsBundle\Model\Notifier\Mailer;

/**
 * ModelQueueInterface
 * 
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
interface ModelQueueInterface
{
    const STATUS_NOT_SENT = "not_sent";
    const STATUS_SENT = "sent";
    const STATUS_FAIL = "fail";
    
    const ATTACH_DOCUMENTS = "attach_documents";
}
