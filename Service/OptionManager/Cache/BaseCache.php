<?php

namespace Maximosojo\ToolsBundle\Service\OptionManager\Cache;

/**
 * Base para cache
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
abstract class BaseCache implements CacheInterface 
{
    protected $options;

    /**
     */
    protected $adapter;
    
    protected function getId($key,$wrapperName): string
    {
        return sprintf("%s___%s",strtoupper($wrapperName),md5(strtoupper($key)));
    }
    
    public function setAdapter(\Maximosojo\ToolsBundle\Service\OptionManager\Adapter\OptionAdapterInterface $adapter) {
        $this->adapter = $adapter;
        return $this;
    }
    
    /**
     * @param type $key
     * @param type $wrapperName
     * @return BaseEntity\ConfigurationInterface
     */
    public function getOption($key, $wrapperName)
    {
        if($this->contains($key, $wrapperName)){
            $data = $this->fetch($key, $wrapperName);
            $option = $this->adapter->createNew();
            $option->setValue($data["value"]);
            return $option;
        }

        return null;
    }
    
    /**
     * Encriptar text
     * @see https://gist.github.com/odan/c1dc2798ef9cedb9fedd09cdfe6e8e76
     * @param type $data
     * @return type
     */
    protected function encrypt($data)
    {
        $ivSize = openssl_cipher_iv_length($this->options["method_encrypt"]);
        $iv = openssl_random_pseudo_bytes($ivSize);

        $encrypted = openssl_encrypt($data, $this->options["method_encrypt"], $this->options["key"], OPENSSL_RAW_DATA, $iv);

        // For storage/transmission, we simply concatenate the IV and cipher text
        $encrypted = base64_encode($iv . $encrypted);

        return $encrypted;
    }

    protected function decrypt($data)
    {
        $data = base64_decode($data);
        $ivSize = openssl_cipher_iv_length($this->options["method_encrypt"]);
        $iv = substr($data, 0, $ivSize);
        $data = openssl_decrypt(substr($data, $ivSize), $this->options["method_encrypt"], $this->options["key"], OPENSSL_RAW_DATA, $iv);

        return $data;
    }
}
