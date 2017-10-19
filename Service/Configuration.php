<?php

/*
 * This file is part of the Atechnologies package.
 * 
 * (c) www.atechnologies.com.ve
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Atechnologies\ToolsBundle\Service;

/**
 * Configuracion general
 * Service (atechnologies.service.configuration)
 * 
 * @author Máximo Sojo maxsojo13@gmail.com <maxtoan at atechnologies> 
 */
class Configuration extends BaseService
{
    /**
     * Formato de fechas
     */
    const GENERAL_DATE_FORMAT = 'GENERAL_DATE_FORMAT';
  
    /**
     * Obtiene el tiempo de que se tomara en cuenta para mostrar los indicadores
     * 
     * @param string $default
     */
    function getGeneralDateFormat($default = 'Y-m-d h:i a')
    {
        return $this->getContainer()->get(self::GENERAL_DATE_FORMAT, $default);
    }
}
