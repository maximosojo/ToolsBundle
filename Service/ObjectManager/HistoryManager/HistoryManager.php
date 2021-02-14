<?php

namespace Maxtoan\ToolsBundle\Service\ObjectManager\HistoryManager;

use Maxtoan\ToolsBundle\Service\ObjectManager\HistoryManager\Adapter\HistoryAdapterInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Maxtoan\ToolsBundle\Service\ObjectManager\TraitConfigure;

/**
 * Administrador del historial
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class HistoryManager implements HistoryAdapterInterface
{
    use TraitConfigure;
    use ContainerAwareTrait;

    /**
     * Adaptador
     * @var HistoryAdapterInterface 
     */
    private $adapter;
    
    /**
     * Opciones de configuracion
     * @var array
     */
    private $options;
    
    public function __construct(HistoryAdapterInterface $adapter = null,array $options = [])
    {
        $this->adapter = $adapter;
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            "debug" => false,
        ]);
        $this->options = $resolver->resolve($options);
    }

    
    public function create(\Maxtoan\ToolsBundle\Service\ObjectManager\HistoryManager\HistoryInterface $entity)
    {
        return $this->adapter->create($entity);
    }

    public function delete(\Maxtoan\ToolsBundle\Service\ObjectManager\HistoryManager\HistoryInterface $entity)
    {
        return $this->adapter->delete($entity);
    }

    public function find($id)
    {
        return $this->adapter->find($id);
    }

    public function getPaginator(array $criteria = array(), array $sortBy = array())
    {
        return $this->adapter->getPaginator($criteria,$sortBy);
    }
}
