<?php

namespace Maxtoan\ToolsBundle\Controller\ObjectManager;

use Symfony\Component\HttpFoundation\Request;
use Maxtoan\ToolsBundle\Form\ObjectManager\DocumentManager\DocumentsType;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * Controlador de manejador de documentos
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class DocumentManagerController extends ManagerController
{
    /**
     * Sube un documento
     *
     * @param   Request  $request
     * @return  returnUrl
     */
    public function uploadAction(Request $request)
    {
        $objectDataManager = $this->getObjectDataManager($request);
        
        $form = $this->createForm(DocumentsType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $documents = $form->get("documents")->getData();
            $comments = $form->get("comments")->getData();
            foreach ($documents as $document) {
                $objectDataManager->documents()->upload($document,[
                    "comments" => $comments,
                    "name" => $request->get("name")
                ]);
            }
        }
        return $this->toReturnUrl();
    }
    
    /**
     * Remueve un documento
     *
     * @param   Request  $request
     * @return  returnUrl
     */
    public function deleteAction(Request $request)
    {
        $objectDataManager = $this->getObjectDataManager($request);
        $objectDataManager->documents()->delete($request->get("filename"));
        return $this->toReturnUrl();
    }
    
    /**
     * Busca un documento
     *
     * @param   Request  $request
     * @return  BinaryFileResponse $response
     */
    public function getAction(Request $request)
    {
        $fileName = $request->get("filename");

        // ObjectDataManager
        $objectDataManager = $this->getObjectDataManager($request);
        $disposition = $request->get("disposition",ResponseHeaderBag::DISPOSITION_ATTACHMENT);
        $file = $objectDataManager->documents()->get($fileName);
        
        // BinaryFileResponse
        $response = new \Symfony\Component\HttpFoundation\BinaryFileResponse($file);
        $response->setContentDisposition($disposition);
        return $response;
    }
}