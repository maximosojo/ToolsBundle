<?php

namespace Maximosojo\ToolsBundle\Model\Core\Configuration;

/**
 * Model de general
 * 
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
abstract class ConfigurationWrapper implements WrapperInterface
{
	private $manager;
	
	public function get($key)
	{
		return $this->manager->get($key);
	}

	public function set($key,$value = null)
	{
		return $this->manager->set($key,$value);
	}

	/**
	 * @author Máximo Sojo <maxsojo13@gmail.com>
	 * @return Exception
	 */
    public static function getName()
    {
        throw new \Exception("Not implement", 1);
    }

    public function setManager(\Maximosojo\ToolsBundle\Service\Core\Configuration\ConfigurationManager $manager)
    {
        $this->manager = $manager;
        return $this;
    }
}