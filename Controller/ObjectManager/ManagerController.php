<?php

namespace Maxtoan\ToolsBundle\Controller\ObjectManager;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Maxtoan\ToolsBundle\Service\ObjectManager\ObjectDataManager;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Base de controladores del manager
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
abstract class ManagerController extends AbstractController
{
    /**
     * Configuracion de la instancia
     * @var array
     */
    protected $config;

    /**
     * Obtiene y configura desde el request el ObjectDataManager
     * @param Request $request
     * @return ObjectDataManager
     */
    protected function getObjectDataManager(Request $request)
    {
        // Se resuelven las opciones
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            "folder" => null,
        ]);
        $resolver->setRequired(["returnUrl","objectId","objectType"]);
        $config = $resolver->resolve($request->get("_conf"));
        $this->config = $config;

        return $this->container->get("maxtoan_tools.object_manager");
    }

    /**
     * Obtiene el Document Manager y configura desde el request el ObjectDataManager
     * @param Request $request
     * @return DocumentManager
     */
    protected function getDocumentManager(Request $request)
    {
        $objectDataManager = $this->getObjectDataManager($request);
        $documentManager = $objectDataManager->documents();
        $documentManager->configure($this->config["objectId"],$this->config["objectType"]);
        $documentManager->folder($this->config["folder"]);
        return $documentManager;
    }

    /**
     * Obtiene el Document Manager y configura desde el request el ObjectDataManager
     * @param Request $request
     * @return DocumentManager
     */
    protected function getExporterManager(Request $request)
    {
        $objectDataManager = $this->getObjectDataManager($request);
        $exporterManager = $objectDataManager->exporters();
        $exporterManager->configure($this->config["objectId"],$this->config["objectType"]);
        return $exporterManager;
    }
    
    /**
     * Redirecciona a la url de retorno
     * @return type
     */
    protected function toReturnUrl()
    {
        return $this->redirect($this->config["returnUrl"]);
    }
}
