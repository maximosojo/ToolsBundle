<?php

/*
 * This file is part of the Máximo Sojo - maxtoan package.
 * 
 * (c) https://maxtoan.github.io/common
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maximosojo\ToolsBundle\Service\Util;

/**
 * Util basico fechas
 * 
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
class DateUtil
{
    /**
     * Retorna o contruye una fecha desde
     * @param \DateTime $date
     * @return \DateTime
     */
    public static function getDateFrom(\DateTime $date = null,$day = null,$month = null,$year = null)
    {
        if($date === null){
            $date = new \DateTime();
        }else{
            $date = clone($date);
        }
        $date->setTime(0, 0, 0);
        if($year !== null){
            $date->setDate($year, $date->format("m"), $date->format("d"));
        }
        if($month !== null){
            $date->setDate($date->format("Y"), $month, $date->format("d"));
        }
        if($day !== null){
            $date->setDate($date->format("Y"), $date->format("m"), $day);
        }
        return $date;
    }
    
    /**
     * Retorna o contruye una fecha hasta
     * @param \DateTime $date
     * @return \DateTime
     */
    public static function getDateTo(\DateTime $date = null,$day = null,$month = null,$year = null)
    {
        if($date === null){
            $date = new \DateTime();
        }else{
            $date = clone($date);
        }
        $date->setTime(23, 59, 59);
        if($year !== null){
            $date->setDate($year, $date->format("m"), $date->format("d"));
        }
        if($month !== null){
            $date->setDate($date->format("Y"), $month, $date->format("d"));
        }
        if($day !== null){
            $date->setDate($date->format("Y"), $date->format("m"), $day);
        }
        return $date;
    }
}
