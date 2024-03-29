<?php

/*
 * This file is part of the Maximosojo Tools package.
 * 
 * (c) https://maximosojo.github.io/tools-bundle
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maximosojo\ToolsBundle\Component\Console\Command;

use Psr\Log\LogLevel;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Lock\Factory;
use Symfony\Component\Lock\Store\SemaphoreStore;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Maximosojo\ToolsBundle\DependencyInjection\ContainerAwareTrait;

/**
 * Command
 * 
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
class Command extends SymfonyCommand
{    
    use ContainerAwareTrait;
    
    /**
     * @var \Symfony\Component\Console\Style\SymfonyStyle
     */
    protected $io;

    /**
     * Shortcut to return the Doctrine Registry service.
     *
     * @return \Doctrine\Bundle\DoctrineBundle\Registry
     *
     * @throws LogicException If DoctrineBundle is not available
     */
    protected function getDoctrine() 
    {
        if (!$this->getContainer()->has('doctrine')) {
            throw new LogicException('The DoctrineBundle is not registered in your application.');
        }

        return $this->getContainer()->get('doctrine');
    }
    
    /**
     * Retorna un loger de consola
     * @return ConsoleLogger
     */
    protected function getLogger() 
    {
        $levelLogger = array(
            LogLevel::NOTICE => OutputInterface::VERBOSITY_NORMAL,
            LogLevel::INFO => OutputInterface::VERBOSITY_NORMAL
        );
        
        return new ConsoleLogger($this->io, $levelLogger);
    }

    /**
     * Creación de instancia Factory Lock
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @return Factory
     */
    public function createFactoryLock($resource = '', $ttl = 300.0, $autoRelease = true)
    {
        $store = new SemaphoreStore();
        $factory = new Factory($store);
        return $factory->createLock($resource,$ttl,$autoRelease);
    }

    public function getContainer()
    {
        $kernel = $this->getApplication()->getKernel();
        return $kernel->getContainer();
    }
}
