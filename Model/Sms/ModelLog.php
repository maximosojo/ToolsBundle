<?php

/*
 * This file is part of the Maxtoan Tools package.
 * 
 * (c) https://maximosojo.github.io/tools-bundle
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maxtoan\ToolsBundle\Model\Sms;

use Doctrine\ORM\Mapping as ORM;

/**
 * Modelo logs
 * 
 * @author Máximo Sojo <maxsojo13@gmail.com>
 * @ORM\MappedSuperclass()
 */
class ModelLog
{
    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(name="id", type="string", length=255)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Mensaje
     * @var type 
     * @ORM\Column(name="content",type="string")
     */
    protected $content;

    /**
     * Contenido en MD5
     * @var type 
     * @ORM\Column(name="md5_content",type="string",nullable=true,length=32)
     */
    protected $md5Message;
    
    /**
     * Origen
     * @var type 
     * @ORM\Column(name="origin",type="string")
     */
    protected $origin;

    /**
     * Action
     * @var type 
     * @ORM\Column(name="action",type="string")
     */
    protected $action;

    /**
     * Usuario propietario del mensaje
     * @var User
     */
    protected $user;

    /**
     * Es el remitente
     * @var string
     * @ORM\Column(name="sender", type="string", length=13, nullable=true)
     */
    protected $sender;

    public function getId()
    {
        return $this->id;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    public function getMd5Message()
    {
        return $this->md5Message;
    }

    public function setMd5Message($md5Message)
    {
        $this->md5Message = $md5Message;
        return $this;
    }

    /**
     * Set action
     *
     * @param string $action
     *
     * @return Log
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Get action
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set sender
     *
     * @param string $sender
     *
     * @return Log
     */
    public function setSender($sender)
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * Get sender
     *
     * @return string
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * Set user
     *
     * @param User $user
     *
     * @return Log
     */
    public function setUser($user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    public function getOrigin()
    {
        return $this->origin;
    }

    public function setOrigin($origin)
    {
        $this->origin = $origin;

        return $this;
    }
}
