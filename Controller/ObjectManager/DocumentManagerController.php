<?php

namespace Maximosojo\ToolsBundle\Controller\ObjectManager;

use Symfony\Component\HttpFoundation\Request;
use Maximosojo\ToolsBundle\Form\ObjectManager\DocumentManager\DocumentsType;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Maximosojo\ToolsBundle\Service\ObjectManager\ObjectDataManagerInterface;
use Maximosojo\ToolsBundle\MaximosojoToolsEvents;

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
    public function uploadAction(Request $request, ObjectDataManagerInterface $objectDataManager)
    {
        $documentManager = $this->getDocumentManager($request,$objectDataManager);
        
        $form = $this->createForm(DocumentsType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $documents = $form->get("documents")->getData();
            $comments = $form->get("comments")->getData();
            foreach ($documents as $document) {
                $file = $documentManager->upload($document,[
                    "comments" => $comments,
                    "name" => $request->get("name")
                ]);
                $this->dispatch(MaximosojoToolsEvents::DOCUMENT_MANAGER_UPLOAD,$this->newGenericEvent($file));
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
    public function deleteAction(Request $request, ObjectDataManagerInterface $objectDataManager)
    {
        $documentManager = $this->getDocumentManager($request,$objectDataManager);
        $file = $documentManager->get($request->get("filename"));
        $this->dispatch(MaximosojoToolsEvents::DOCUMENT_MANAGER_DELETE,$this->newGenericEvent($file));
        $documentManager->delete($request->get("filename"));
        $referer = $request->headers->get('referer');
        return new RedirectResponse($referer);
    }

    /**
     * Descarga un documento
     *
     * @param   Request  $request
     * @return  BinaryFileResponse
     */
    public function downloadAction(Request $request, ObjectDataManagerInterface $objectDataManager)
    {
        $documentManager = $this->getDocumentManager($request,$objectDataManager);
        $file = $documentManager->get($request->get("filename"));

        $this->dispatch(MaximosojoToolsEvents::DOCUMENT_MANAGER_DOWNLOAD,$this->newGenericEvent($file));

        $response = new \Symfony\Component\HttpFoundation\BinaryFileResponse($file);
        $response = new \Symfony\Component\HttpFoundation\BinaryFileResponse($file->getRealPath());
        $response->setContentDisposition(\Symfony\Component\HttpFoundation\ResponseHeaderBag::DISPOSITION_ATTACHMENT);
        return $response;
    }
    
    /**
     * Busca un documento
     *
     * @param   Request  $request
     * @return  BinaryFileResponse $response
     */
    public function getAction(Request $request, ObjectDataManagerInterface $objectDataManager)
    {
        $fileName = $request->get("filename");

        // ObjectDataManager
        $documentManager = $this->getDocumentManager($request,$objectDataManager);
        $disposition = $request->get("disposition",ResponseHeaderBag::DISPOSITION_ATTACHMENT);
        $file = $documentManager->get($fileName);
        
        // BinaryFileResponse
        $response = new \Symfony\Component\HttpFoundation\BinaryFileResponse($file);
        $response->setContentDisposition($disposition);
        return $response;
    }

    /**
     * Visualizar un documento
     *
     * @param   Request  $request
     * @return  BinaryFileResponse $response
     */
    public function viewAction(Request $request, ObjectDataManagerInterface $objectDataManager)
    {
        $fileName = $request->get("filename");

        // ObjectDataManager
        $documentManager = $this->getDocumentManager($request,$objectDataManager);
        $disposition = $request->get("disposition",ResponseHeaderBag::DISPOSITION_INLINE);
        $file = $documentManager->get($fileName);
        
        // BinaryFileResponse
        $response = new \Symfony\Component\HttpFoundation\BinaryFileResponse($file);
        $response->setContentDisposition($disposition);
        return $response;
    }

    public function allAction(Request $request, ObjectDataManagerInterface $objectDataManager)
    {
        $arrayFile = [];
        $documentManager = $this->getDocumentManager($request,$objectDataManager);
        $files = $documentManager->getAll();
        foreach ($files as $file) {
            $arrayFile[] = $documentManager->toArray($file);
        }

        return new \Symfony\Component\HttpFoundation\JsonResponse(["files" => $arrayFile]);
    }
}