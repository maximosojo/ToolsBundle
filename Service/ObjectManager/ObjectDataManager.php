<?php

namespace Maxtoan\ToolsBundle\Service\ObjectManager;

/**
 * Administrador de datos de un objeto (documentos,notas,historial)
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class ObjectDataManager implements ConfigureInterface
{
    /**
     * Manejador de documentos
     * @var DocumentManager\DocumentManager
     */
    private $documentManager;
    
    public function __construct(DocumentManager\DocumentManager $documentManager)
    {
        $this->documentManager = $documentManager;
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
            $this->documentManager->configure($objectId, $objectType);
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
}
