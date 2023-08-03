<?php

namespace Maximosojo\ToolsBundle\Model\Notifier\Texter;

/**
 * ModelMessageInterface
 * 
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
interface ModelMessageInterface
{
    const STATUS_FAILED = 'failed';
    const STATUS_READY = 'ready';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';
}
