<?php

namespace Maximosojo\ToolsBundle\Service\OptionManager\Cache;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Sin cache
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class NoneStored extends BaseCache
{
    private $optionDatas = [];
    
    public function __construct(array $options = [])
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            'folder' => "maximosojo_tools",
            'method_encrypt' => "AES-256-CBC", 
            'password' => "40cb3cbe84491f86c8e53157e5a91b88", 
        ]);
        
        $resolver->setRequired(["password"]);
        
        $this->options = $resolver->resolve($options);
        $this->options["key"] = hash('sha256', $this->options["password"]);
        
    }
    
    public function contains($key, $wrapperName)
    {
        return isset($this->optionDatas[$this->getId($key, $wrapperName)]);
    }

    public function delete($key, $wrapperName)
    {
        if($this->contains($key, $wrapperName)){
            unset($this->optionDatas[$this->getId($key, $wrapperName)]);
        }
    }

    public function fetch($key, $wrapperName)
    {
        //Forzar lectura de la BD para evitar cache de memorira
//        $this->warmUp($this->adapter->findAll());
        
        $data = $this->optionDatas[$this->getId($key, $wrapperName)];
        return $data;
    }

    public function save($key, $wrapperName, $data, $lifeTime = 0)
    {
        $this->optionDatas[$this->getId($key, $wrapperName)] = $data;
    }
    
    public function flush()
    {
        //eliminar cache
        $this->optionDatas = [];
    }
    
    public function warmUp(array $optionDatas)
    {
        $this->optionDatas = [];
        foreach ($optionDatas as $key => $optionData) {
            $data = array();
            $data['value'] = $optionData->getValue();
            $id = $this->getId($optionData->getKey(),$optionData->getNameWrapper());
            $this->optionDatas[$id] = $data;
        }
        
        return true;
    }

}
