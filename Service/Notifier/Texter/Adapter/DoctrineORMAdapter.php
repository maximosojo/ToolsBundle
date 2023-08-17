<?php

namespace Maximosojo\ToolsBundle\Service\Notifier\Texter\Adapter;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Adaptador de doctrine para el servicio de mensajes
 *
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
class DoctrineORMAdapter implements TexterAdapterInterface
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
            "queue_class"
        ]);
        $resolver->setDefaults([
            "connection" => null,
        ]);
        $this->options = $resolver->resolve($options);
    }

    public function createSmsQueue()
    {
        return new $this->options["queue_class"]();
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

    public function remove($entity)
    {
        $em = $this->em;
        $em->remove($entity);
    }
}
