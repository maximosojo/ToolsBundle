<?php

namespace Maxtoan\ToolsBundle\Service\ObjectManager;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Maxtoan\ToolsBundle\DependencyInjection\ContainerAwareTrait;

/**
 * Administrador de datos de un objeto (documentos,notas,historial)
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
class ObjectDataManager implements ConfigureInterface, ObjectDataManagerInterface
{
    use ContainerAwareTrait;

    /**
     * @var array
     */
    private $options;

    private $objectId;

    private $objectType;
    
    public function setOptions($options)
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            "document_manager" => [
                "template" => null
            ],
            "exporter_manager" => [
                "template" => null
            ],
            "statistics_manager" => [],
            "history_manager" => []
        ]);
        $resolver->setDefined(["object_types"]);
        $this->options = $resolver->resolve($options);
    }
    
    /**
     * Configura el servicio para manejar un objeto y tipo en especifico
     * @param type $objectId
     * @param type $objectType
     * @return \Maxtoan\ToolsBundle\Service\ObjectManager\DocumentManager\DocumentManager
     */
    public function configure($objectId, $objectType)
    {
        $this->objectId = $objectId;
        $this->objectType = $objectType;
        return $this;
    }
    
    /**
     * Retorna el manejador de documentos
     * @return DocumentManager\DocumentManager
     */
    public function documents()
    {
        $documentManager = $this->container->get("maxtoan_tools.document_manager");
        $documentManager->configure($this->objectId, $this->objectType);
        return $documentManager;
    }

    /**
     * Retorna el generador de documentos
     * @return ExporterManager\ExporterManager
     */
    public function exporters()
    {
        $exporterManager = $this->container->get("maxtoan_tools.exporter_manager");
        $exporterManager->configure($this->objectId, $this->objectType, $this->options["exporter_manager"]);
        return $exporterManager;
    }

    /**
     * @return StatisticManager\StatisticsManager
     * @throws UnconfiguredException
     */
    public function statistics()
    {
        $statisticsManager = $this->container->get("maxtoan_tools.statistics_manager");
        $statisticsManager->configure($this->objectId, $this->objectType, $this->options["statistics_manager"]);
        return $statisticsManager;
    }

    /**
     * Retorna el manejador de historiales
     * @return HistoryManager\HistoryManager
     */
    public function histories()
    {
        $historyManager = $this->container->get("maxtoan_tools.history_manager");
        $historyManager->configure($this->objectId, $this->objectType, $this->options["history_manager"]);
        return $historyManager;
    }
}
