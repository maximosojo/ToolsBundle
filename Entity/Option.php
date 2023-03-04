<?php

/*
 * This file is part of the Máximo Sojo - maximosojo package.
 * 
 * (c) https://maximosojo.github.io/
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maximosojo\ToolsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Maximosojo\ToolsBundle\Service\OptionManager\OptionInterface;

/**
 * Option
 *
 * @author Máximo Sojo <maxsojo13@gmail.com>
 * 
 * @ORM\Entity()
 * @ORM\MappedSuperclass()
 * @ORM\Table(name="options")
 */
class Option implements OptionInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Indice de configuracion
     * 
     * @var string
     * @ORM\Column(name="option_key", type="string", length=200)
     */
    protected $key;
    
    /**
     * Valor de configuracion
     * 
     * @var string
     * @ORM\Column(name="option_value", type="text", nullable=true)
     */
    protected $value;

    /**
     * Valor de configuracion
     * 
     * @var string
     * @ORM\Column(name="wrapper", type="string", length=200)
     */
    protected $wrapper;
    
    public function getId()
    {
        return $this->id;
    }

    public function setKey($key): self
    {
        $this->key = $key;

        return $this;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function setValue($value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setWrapper($wrapper): self
    {
        $this->wrapper = $wrapper;

        return $this;
    }

    public function getWrapper()
    {
        return $this->wrapper;
    }
}
