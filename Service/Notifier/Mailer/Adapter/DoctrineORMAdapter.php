<?php

namespace Maximosojo\ToolsBundle\Service\Notifier\Mailer\Adapter;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Adaptador de doctrine para el servicio de correo
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class DoctrineORMAdapter implements EmailAdapterInterface
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface 
     */
    protected $em;
    
    protected $options;
    
    public function __construct(\Doctrine\ORM\EntityManagerInterface $em, array $options = [])
    {
        $this->em = $em;
        $resolver = new OptionsResolver();
        
        $resolver->setRequired([
            "template_class", 
            "component_class",
            "queue_class"
        ]);
        $resolver->setDefaults([
            "connection" => null,
        ]);
        $this->options = $resolver->resolve($options);
    }

    public function createEmailQueue()
    {
        return new $this->options["queue_class"]();
    }

    public function find($id)
    {
        return $this->em->find($this->options["template_class"], $id);
    }

    public function flush()
    {
        $this->em->flush();
    }

    public function persist($entity)
    {
        $em = $this->em;
        $em->persist($entity);
    }

    public function createComponent()
    {
        return new $this->options["component_class"]();
    }
    
    public function createEmailTemplate()
    {
        return new $this->options["template_class"]();
    }

    public function remove($entity)
    {
        $em = $this->em;
        $em->remove($entity);
    }
}
