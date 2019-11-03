<?php

namespace Maxtoan\ToolsBundle\Service\Mailer\Adapter;

/**
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
interface EmailAdapterInterface
{    
    /**
     * 
     * @param \Maxtoan\ToolsBundle\Interfaces\Email\TemplateInterface $id
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
     * @return \Maxtoan\ToolsBundle\Interfaces\Email\ComponentInterface
     */
    public function createComponent();
    
    /**
     * @return \Maxtoan\ToolsBundle\Interfaces\Email\ComponentInterface
     */
    public function createEmailTemplate();
}
