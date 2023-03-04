<?php

/*
 * This file is part of the Maximosojo Tools package.
 * 
 * (c) https://maximosojo.github.io/tools-bundle
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maximosojo\ToolsBundle\Service\OptionManager\Wrapper;

use Maximosojo\ToolsBundle\Service\OptionManager\OptionManagerInterface;

/**
 * Base para wrappers
 *
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
abstract class BaseWrapper
{    
    /**
     * @var OptionManagerInterface
     */
    private $manager;
    
    /**
     * Guarda o actualiza la configuracion en la base de datos y regenera la cache
     * 
     * @param type $key
     * @param type $value
     * @param type $description
     * @return Configuration
     */
    public function set($key,$value = null,$description = null)
    {
        return $this->manager->set($key, $value,$this->getName(),$description);
    }
    
    /**
     * Obtiene el valor del indice
     * 
     * @param type $key
     * @param type $default
     * @return type
     */
    public function get($key,$default = null)
    {
        return $this->manager->get($key,$this->getName(),$default);
    }
    
    
    public final function clearCache()
    {
        $this->manager->clearCache();
        $this->manager->warmUp();
    }


    public function setManager(OptionManagerInterface $manager)
    {
        $this->manager = $manager;

        return $this;
    }
    
    public abstract static function getName();
}
