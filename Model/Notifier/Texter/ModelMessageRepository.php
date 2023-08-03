<?php

namespace Maximosojo\ToolsBundle\Model\Notifier\Texter;

use Maximosojo\ToolsBundle\ORM\EntityRepository;
use Maximosojo\ToolsBundle\Model\Notifier\Texter\ModelMessage;

/**
 * Repositorio de cola de mensajes
 *
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
class ModelMessageRepository extends EntityRepository
{
    /**
     * Retorna un paginador con los correos pendientes por construir y enviar
     * @param string $environment
     * @return Paginator
     */
    public function getPendings($environment)
    {
        $a = $this->getAlias();

        $qb = $this->getQueryBuilder();

        $qb
           ->andWhere($a.".status = :status")
           ->andWhere($a.".environment = :environment")
           ->setParameter("status",ModelMessage::STATUS_READY)
           ->setParameter("environment",$environment)
           ;

        return $this->getPaginator($qb);
    }
    
    public function getAlias()
    {
        return "sq";
    }
}
