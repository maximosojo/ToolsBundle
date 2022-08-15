<?php

/*
 * This file is part of the BtoB4Rewards package.
 * 
 * (c) www.btob4rewards.com
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maximosojo\ToolsBundle\Service\ObjectManager\StatisticManager\Adapter;

/**
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
interface StatisticsAdapterInterface 
{
    public function persist($entity);
    public function flush();
    
    /**
     * @return \Maximosojo\ToolsBundle\Model\Configuration\Statistics\StatisticsYearInterface Description
     */
    public function newYearStatistics(\Maximosojo\ToolsBundle\Service\ObjectManager\StatisticManager\StatisticsManager $statisticsManager);
    /**
     * @return \Maximosojo\ToolsBundle\Model\Configuration\Statistics\StatisticsMonthInterface Description
     */
    public function newStatisticsMonth(\Maximosojo\ToolsBundle\Service\ObjectManager\StatisticManager\StatisticsManager $statisticsManager);
    
    /**
     * Busca las estadisticas de un año
     * @param array $params
     * @return \Maximosojo\ToolsBundle\Model\Statistics\StatisticsYearInterface
     */
    public function findStatisticsYear(array $params = array());
}
