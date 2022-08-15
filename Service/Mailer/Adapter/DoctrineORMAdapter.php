<?php

namespace Maximosojo\ToolsBundle\Service\Mailer\Adapter;

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
            "mailer_template_class", 
            "mailer_component_class",
        ]);
        $resolver->setDefaults([
            "connection" => null,
        ]);
        $this->options = $resolver->resolve($options);
    }

    public function createEmailQueue()
    {
        return new $this->options["mailer_queue_class"]();
    }

    public function find($id)
    {
        return $this->em->find($this->options["mailer_template_class"], $id);
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
        return new $this->options["mailer_component_class"]();
    }
    
    public function createEmailTemplate()
    {
        return new $this->options["mailer_template_class"]();
    }

    public function remove($entity)
    {
        $em = $this->em;
        $em->remove($entity);
    }
}
