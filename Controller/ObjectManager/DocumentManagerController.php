<?php

namespace Maxtoan\ToolsBundle\Controller\ObjectManager;

use Symfony\Component\HttpFoundation\Request;
use Maxtoan\ToolsBundle\Form\ObjectManager\DocumentManager\DocumentsType;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\RedirectResponse;

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
        $documentManager = $this->getDocumentManager($request);
        
        $form = $this->createForm(DocumentsType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $documents = $form->get("documents")->getData();
            $comments = $form->get("comments")->getData();
            foreach ($documents as $document) {
                $documentManager->upload($document,[
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
        $documentManager = $this->getDocumentManager($request);
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
    public function downloadAction(Request $request)
    {
        $documentManager = $this->getDocumentManager($request);
        $file = $documentManager->get($request->get("filename"));
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
    public function getAction(Request $request)
    {
        $fileName = $request->get("filename");

        // ObjectDataManager
        $documentManager = $this->getDocumentManager($request);
        $disposition = $request->get("disposition",ResponseHeaderBag::DISPOSITION_ATTACHMENT);
        $file = $documentManager->get($fileName);
        
        // BinaryFileResponse
        $response = new \Symfony\Component\HttpFoundation\BinaryFileResponse($file);
        $response->setContentDisposition($disposition);
        return $response;
    }

    public function allAction(Request $request)
    {
        $arrayFile = [];
        $documentManager = $this->getDocumentManager($request);
        $files = $documentManager->getAll();
        foreach ($files as $file) {
            $arrayFile[] = $documentManager->toArray($file);
        }

        return new \Symfony\Component\HttpFoundation\JsonResponse(["files" => $arrayFile]);
    }
}