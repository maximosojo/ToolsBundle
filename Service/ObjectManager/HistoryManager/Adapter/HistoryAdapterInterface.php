<?php

namespace Maxtoan\ToolsBundle\Service\ObjectManager\HistoryManager\Adapter;

use Maxtoan\ToolsBundle\Service\ObjectManager\HistoryManager\HistoryInterface;
use Maxtoan\ToolsBundle\Service\ObjectManager\ConfigureInterface;

/**
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
interface HistoryAdapterInterface extends ConfigureInterface
{
    public function create(HistoryInterface $entity);
    
    public function delete(HistoryInterface $entity);
    
    public function find($id);
    
    public function getPaginator(array $criteria = [],array $sortBy = []);
}
