<?php

namespace Maxtoan\ToolsBundle\Service\ObjectManager;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Administrador de datos de un objeto (documentos,notas,historial)
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
class ObjectDataManager implements ConfigureInterface, ObjectDataManagerInterface
{
    /**
     * @var array
     */
    private $options;

    /**
     * Manejador de documentos
     * @var DocumentManager\DocumentManager
     */
    private $documentManager;

    /**
     * Generador de documentos
     * @var ExporterManager\ExporterManager
     */
    private $exporterManager;

    /**
     * @var StatisticManager\StatisticsManager
     */
    private $statisticsManager;
    
    public function __construct(DocumentManager\DocumentManager $documentManager, ExporterManager\ExporterManager $exporterManager, StatisticManager\StatisticsManager $statisticsManager)
    {
        $this->documentManager = $documentManager;
        $this->exporterManager = $exporterManager;
        $this->statisticsManager = $statisticsManager;
    }
    
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
            "statistics_manager" => []
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
        if($this->documentManager){
            $this->documentManager->configure($objectId, $objectType, $this->options["document_manager"]);
        }

        if($this->exporterManager){
            $this->exporterManager->configure($objectId, $objectType, $this->options["exporter_manager"]);
        }

        if($this->statisticsManager){
            $this->statisticsManager->configure($objectId, $objectType,$this->options["statistics_manager"]);
        }

        return $this;
    }
    
    /**
     * Retorna el manejador de documentos
     * @return DocumentManager\DocumentManager
     */
    public function documents()
    {
        if(!$this->documentManager){
            throw new UnconfiguredException(sprintf("El '%s' no esta configurado para usar esta caracteristica.",DocumentManager\DocumentManager::class));
        }
        return $this->documentManager;
    }

    /**
     * Retorna el generador de documentos
     * @return ExporterManager\ExporterManager
     */
    public function exporters()
    {
        if(!$this->exporterManager){
            throw new UnconfiguredException(sprintf("El '%s' no esta configurado para usar esta caracteristica.",ExporterManager\ExporterManager::class));
        }
        return $this->exporterManager;
    }

    /**
     * @return StatisticManager\StatisticsManager
     * @throws UnconfiguredException
     */
    public function statistics()
    {
        if(!$this->statisticsManager){
            throw new UnconfiguredException(sprintf("El '%s' no esta configurado para usar esta caracteristica.", StatisticManager\StatisticsManager::class));
        }
        return $this->statisticsManager;
    }
}
