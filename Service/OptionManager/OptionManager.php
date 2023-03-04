<?php

/*
 * This file is part of the Maximosojo Tools package.
 * 
 * (c) https://maximosojo.github.io/tools-bundle
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maximosojo\ToolsBundle\Service\OptionManager;

use Symfony\Component\OptionsResolver\OptionsResolver;
use RuntimeException;
use Maximosojo\ToolsBundle\Service\OptionManager\Wrapper\DefaultWrapper;

/**
 * Menjador de opciones
 *
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
class OptionManager implements OptionManagerInterface
{
    /**
     * @var array
     */
    protected $options = array();

    /**     
     * Adaptador origen de los datos
     * @var Adapter\OptionAdapterInterface
     */
    private $adapter;
    
    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * Wrappers
     */
    private $wrappers;

    function __construct(Adapter\OptionAdapterInterface $adapter, Cache\CacheInterface $cache, array $options = array())
    {
        if(!class_exists("Symfony\Component\Config\ConfigCache")){
            throw new \Exception(sprintf("The package '%s' is required, please install https://packagist.org/packages/symfony/config",'"symfony/config": "^3.1"'));
        }

        if(!class_exists("Symfony\Component\OptionsResolver\OptionsResolver")){
            throw new \Exception(sprintf("The package '%s' is required, please install https://packagist.org/packages/symfony/options-resolver",'"symfony/options-resolver": "^3.1"'));
        }

        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            'debug' => false,
            'add_default_wrapper'  => false
        ]);
        $resolver->addAllowedTypes("add_default_wrapper","boolean");
        
        $this->options = $resolver->resolve($options);
        $this->adapter = $adapter;
        $this->cache = $cache;
    }

    /**
     * Añade un grupo de configuracion
     * @param \Maximosojo\ToolsBundle\Service\OptionManager\Wrapper\BaseWrapper $wrapper
     * @return \Maximosojo\ToolsBundle\Service\OptionManager\Wrapper\BaseWrapper
     * @throws \RuntimeException
     */
    public function addWrapper(\Maximosojo\ToolsBundle\Service\OptionManager\Wrapper\BaseWrapper $wrapper) 
    {
        $name = strtoupper($wrapper->getName());
        if($this->hasWrapper($name)){
            throw new \RuntimeException(sprintf("The configuration name '%s' already added",$wrapper->getName()));
        }

        $wrapper->setManager($this);
        $this->wrappers[$name] = $wrapper;
        return $this;
    }

    /**
     * Retorna el wrapper de una configuracion
     * @param type $name
     * @return \Maximosojo\ToolsBundle\Service\OptionManager\Wrapper\BaseWrapper
     * @throws \RuntimeException
     */
    public function getWrapper(string $name)
    {
        $name = strtoupper($name);
        if(!$this->hasWrapper($name)){
            throw new \RuntimeException(sprintf("The configuration name '%s' is not added",$name));
        }

        return $this->wrappers[$name];
    }

    /**
     * hasWrapper
     *
     * @param   $wrapperName
     * @param   $throwException
     * @param   false
     *
     * @return  bool
     */
    public function hasWrapper($wrapperName,$throwException = false): bool
    {
        $wrapperName = strtoupper($wrapperName);
        
        $added = false;
        if(isset($this->wrappers[$wrapperName])){
            $added = true;
        }else{
            if($throwException === true){
                throw new \InvalidArgumentException(sprintf("The wrapper with name '%s' dont exist.",$wrapperName));
            }
        }

        return $added;
    }

    /**
     * Retorna el valor de la configuracion de la base de datos
     * 
     * @param string $key Indice de la configuracion
     * @param mixed $default Valor que se retornara en caso de que no exista el indice
     * @return mixed
     */
    public function get($key,$wrapperName = null,$default = null)
    {
        $configuration = $this->getOption($key,$wrapperName);
        if($configuration !== null){
            $value = $configuration->getValue();
        }else{
            $value = $default;
        }
        return $value;
    }
    
    /**
     * Establece el valor de una configuracion
     * 
     * @param string $key indice de la configuracion
     * @param mixed $value valor de la configuracion
     * @param string|null $description Descripcion de la configuracion|null para actualizar solo el key
     */
    public function set($key,$value = null,$wrapperName = null,$description = null,$clearCache = false)
    {
        if($wrapperName === null){
            $wrapperName = DefaultWrapper::getName();
        }

        $key = strtoupper($key);
        $wrapperName = strtoupper($wrapperName);
        
        // $this->hasWrapper($wrapperName,true);
        
        $option = $this->adapter->find($key);
        if($option === null){
            $option = $this->adapter->createNew();
            $option->setKey($key);
            $option->setWrapper($wrapperName);
        }

        $option->setValue($value);
        $this->adapter->persist($option);
        $success = $this->adapter->flush();
        
        $isWarmUp = false;
        //Sino existe el valor en la cache se debe refrescar en base la informacion actualizada
        if(!$this->cache->contains($key, $wrapperName)){
            $this->cache->flush();
            $this->warmUp();
            $isWarmUp = true;
        }else{
            $this->cache->save($key, $wrapperName);
        }

        if($success === true && $clearCache){
            $this->clearCache();
            if(!$isWarmUp){
                $this->warmUp();
            }
        }
        
        return $success;
    }

    /**
     * Guarda los cambios en la base de datos
     */
    public function flush($andClearCache = true)
    {
        $this->adapter->flush();
        if($andClearCache){
            $this->clearCache();
            $this->warmUp();
        }
    }
    
    /**
     * Crea la cache
     */
    public function warmUp()
    {
        $configurations = $this->adapter->findAll();

        $this->cache->warmUp($configurations);

        return $this;
    }
    
    /**
     * Limpia la cache
     */
    public function clearCache()
    {
        $this->cache->flush();
        return $this;
    }
    
    /**
     * Retorna la configuracion
     * 
     * @param type $key
     * @param type $wrapperName
     */
    private function getOption($key, $wrapperName = null)
    {
        if($wrapperName === null){
            $wrapperName = DefaultWrapper::getName();
        }

        $key = strtoupper($key);
        $wrapperName = strtoupper($wrapperName);
        $this->cache->setAdapter($this->adapter);
        if(!$this->cache->contains($key, $wrapperName)){
            $this->clearCache();//Pre-calencar cache
            $this->warmUp();//Pre-calencar cache
        }

        return $this->cache->getOption($key, $wrapperName);
    }
}
