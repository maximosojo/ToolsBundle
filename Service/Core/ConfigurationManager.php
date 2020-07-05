<?php

/*
 * This file is part of the Maxtoan Tools package.
 * 
 * (c) https://maximosojo.github.io/tools-bundle
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maxtoan\ToolsBundle\Service\Core;

use App\Entity\M\Core\Configuration;
use Maxtoan\ToolsBundle\Service\BaseService;

/**
 * Service configurations
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
class ConfigurationManager extends BaseService
{
    public function createNew($class = null)
    {
        $entity = new $class();
        $entity->setConfigurationManager($this);
        return $entity;
    }

    /**
     * Conulta de configuración
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @param  $key
     * @return $value
     */
    public function get($key)
    {
        $value = null;
        $config = $this->getConfigurationKey($key);
        if ($config) {
            $value = $config->getValue();
        }

        return $value;
    }

    public function set($key,$value)
    {
        $config = $this->getConfigurationKey($key);
        if ($config) {
            $config->setValue($value);
            $this->emSave($config,false);
        }

        return $config;
    }

    public function getConfigurationKey($key)
    {
        return $this->getRepository(Configuration::class)->findOneByKey($key);
    }
}