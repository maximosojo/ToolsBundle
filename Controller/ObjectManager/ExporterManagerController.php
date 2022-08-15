<?php

namespace Maximosojo\ToolsBundle\Controller\ObjectManager;

use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use FOS\RestBundle\Util\Codes;
use Maximosojo\ToolsBundle\Form\ObjectManager\ExporterManager\DocumentsType;
use Maximosojo\ToolsBundle\Component\HttpFoundation\JsonResponse;

/**
 * Controlador para exportar los documentos
 *
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
class ExporterManagerController extends ManagerController
{
    /**
     * Genera un documento
     * @param   Request  $request
     */ 
    public function generateAction(Request $request) 
    {
        $exporterManager = $this->getExporterManager($request);

        $chain = $exporterManager->resolveChainModel();
        $choices = [];
        $models = $chain->getModels();
        foreach ($models as $model) {
            $choices[$model->getName()] = $model->getName();
        }

        $form = $this->createForm(DocumentsType::class,$choices);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $options = [
                "fileName" => $request->get("fileName")
            ];
            $name = $form->get("name")->getData();
            $exporterManager->generateWithSource($name,$options);
        }
        
        return new JsonResponse([]);
    }
}
