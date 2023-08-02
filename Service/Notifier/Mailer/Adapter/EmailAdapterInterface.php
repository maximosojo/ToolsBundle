<?php

namespace Maximosojo\ToolsBundle\Service\Notifier\Mailer\Adapter;

/**
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
interface EmailAdapterInterface
{    
    /**
     * 
     * @param \Maximosojo\ToolsBundle\Interfaces\Email\TemplateInterface $id
     */
    public function find($id);
    
    /*
     * Guarda los cambios en la base de datos
     */
    public function flush();
    
    public function persist($entity);
    
    public function remove($entity);
    
    /**
     * 
     */
    public function createEmailQueue();
    
    /**
     * @return \Maximosojo\ToolsBundle\Interfaces\Email\ComponentInterface
     */
    public function createComponent();
    
    /**
     * @return \Maximosojo\ToolsBundle\Interfaces\Email\ComponentInterface
     */
    public function createEmailTemplate();
}
