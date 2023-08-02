<?php

namespace Maximosojo\ToolsBundle\Model\Notifier\Mailer;

use Maximosojo\ToolsBundle\ORM\EntityRepository;
use Maximosojo\ToolsBundle\Model\Notifier\Mailer\ModelQueue;

/**
 * Repositorio de cola de email
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class ModelQueueRepository extends EntityRepository
{
    /**
     * Retorna un paginador con los correos pendientes por construir y enviar
     * @param string $environment
     * @return Paginator
     */
    public function getPendings($environment)
    {
       $qb = $this->getQueryBuilder();

       $qb
           ->andWhere("eq.status = :status")
           ->andWhere("eq.environment = :environment")
           ->setParameter("status",ModelQueue::STATUS_NOT_SENT)
           ->setParameter("environment",$environment)
           ;

       return $this->getPaginator($qb);
    }
    
    public function getAlias()
    {
        return "eq";
    }
}
