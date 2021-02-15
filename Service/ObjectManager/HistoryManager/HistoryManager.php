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
     * Adaptadores disponibles
     * @var Adapters
     */
    private $adapters;

    /**
     * Adaptador por defecto
     * @var HistoryAdapterInterface
     */
    private $defaultAdapter;
    
    /**
     * Opciones de configuracion
     * @var array
     */
    private $options;

    /**
     * Objetos validos
     * @var objectValids
     */
    private $objectValids;
    
    public function __construct(HistoryAdapterInterface $adapter = null,array $options = [])
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            "debug" => false,
        ]);
        $this->options = $resolver->resolve($options);
        $this->defaultAdapter = $adapter;
    }

    /**
     * Registro de configuraciones
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @param  $objectId
     * @param  $objectType
     */
    public function configure($objectId, $objectType, array $options = [])
    {
        $this->adapter = $this->defaultAdapter;
        if (isset($this->adapters[$objectType])) {
            $this->adapter = $this->adapters[$objectType];
        }
        if ($this->adapter === null) {
            throw new \RuntimeException(sprintf("No hay ningun adaptador configurado para '%s' en '%s' debe agregar por lo menos uno.", $objectType, HistoryManager::class));
        }
        
        $this->objectId = $objectId;
        $this->objectType = $objectType;
        
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            "current_ip" => null,
        ]);
        $this->options = $resolver->resolve($options);
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

        $resolver->setDefaults([
            "description" => "",
            "objectId" => $this->objectId,
            "objectType" => $this->objectType,
            "extra" => []
        ]);

        $resolver->setRequired(["eventName","type","description","objectId","objectType"]);

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

    /**
     * Agrega un adaptador
     * @param HistoryAdapterInterface $adapter
     */
    public function addAdapter(HistoryAdapterInterface $adapter, $objectType)
    {
        $this->adapters[$objectType] = $adapter;
        
        return $this;
    }
}
