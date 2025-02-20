<?php

namespace Maximosojo\ToolsBundle\Model\Notifier\Mailer;

/**
 * ModelQueueInterface
 * 
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
interface ModelQueueInterface
{
    const STATUS_FAILED = 'failed';
    const STATUS_READY = 'ready';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';
    
    const ATTACH_DOCUMENTS = "attach_documents";
}
