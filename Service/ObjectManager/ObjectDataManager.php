<?php

namespace Maximosojo\ToolsBundle\Service\ObjectManager;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Maximosojo\ToolsBundle\DependencyInjection\ContainerAwareTrait;
use Maximosojo\ToolsBundle\Service\ObjectManager\StatisticManager\StatisticsManagerInterface;

/**
 * Administrador de datos de un objeto (documentos,notas,historial)
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
class ObjectDataManager implements ObjectDataManagerInterface
{
    use ContainerAwareTrait;

    /**
     * @var array
     */
    private $options;

    protected $statisticsManager;
    
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
     * Retorna el manejador de documentos
     * @return DocumentManager\DocumentManager
     */
    public function documents()
    {
        return $this->container->get("maximosojo_tools.document_manager.default");
    }

    /**
     * Retorna el generador de documentos
     * @return ExporterManager\ExporterManager
     */
    public function exporters()
    {
        return $this->container->get("maximosojo_tools.exporter_manager.default");
    }

    /**
     * @return StatisticManager\StatisticsManager
     * @throws UnconfiguredException
     */
    public function statistics()
    {
        return $this->statisticsManager;
    }

    /**
     * StatisticsManagerInterface
     *
     * @author  Máximo Sojo <maxsojo13@gmail.com>
     *
     * @required
     */
    public function setStatisticsManager(StatisticsManagerInterface $statisticsManager)
    {
        $this->statisticsManager = $statisticsManager;
    }

    /**
     * Retorna el manejador de historiales
     * @return HistoryManager\HistoryManager
     */
    public function histories()
    {
        return $this->container->get("maximosojo_tools.history_manager.default");
    }
}
