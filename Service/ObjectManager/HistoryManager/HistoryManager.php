<?php

namespace Maxtoan\ToolsBundle\Service\ObjectManager\HistoryManager;

use Maxtoan\ToolsBundle\Service\ObjectManager\HistoryManager\Adapter\HistoryAdapterInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Maxtoan\ToolsBundle\Service\ObjectManager\TraitConfigure;
use Maxtoan\ToolsBundle\Service\ObjectManager\HistoryManager\HistoryInterface;

/**
 * Administrador del historial
 *
 * @author Máximo Sojo <maxsojo13@gmail.com>
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
            "debug" => false
        ]);
        $this->options = $resolver->resolve($options);
    }

    /**
     * Registro de configuraciones
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @param  $objectId
     * @param  $objectType
     */
    public function configure($objectId, $objectType)
    {
        $this->objectId = $objectId;
        $this->objectType = $objectType;
        $this->adapter->configure($objectId, $objectType);
    }
    
    public function create(array $options = [])
    {
        $resolver = new OptionsResolver();
        $resolver->setDefined("eventName");
        $resolver->setDefined("type");
        $resolver->setDefined("description");
        $resolver->setDefined("extra");
        $resolver->setDefined("objectId");
        $resolver->setDefined("objectType");
        $resolver->setDefined("user");

        $resolver->setDefaults([
            "description" => "",
            "objectId" => $this->objectId,
            "objectType" => $this->objectType,
            "extra" => []
        ]);

        $resolver->setRequired(["eventName","type","description","objectId","objectType","user"]);

        $options = $resolver->resolve($options);
        
        return $this->adapter->create($options);
    }

    public function delete(HistoryInterface $entity)
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
