<?php

namespace Maxtoan\ToolsBundle\Model\ObjectManager\ExporterManager\Model;

use Exception;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Maxtoan\ToolsBundle\Model\ObjectManager\ExporterManager\ModelDocumentExporter;
use Maxtoan\ToolsBundle\Service\Template\TemplateService;
use App\Entity\M\Master\Exporter\Template;

/**
 * Modelo de excel
 *
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
class ModelExcel extends ModelDocumentExporter
{
    use ContainerAwareTrait;

    /**
     * @var TemplateService
     */
    private $templateService;

    public function getFormat()
    {
        return "excel";
    }

    public function write(array $variables = [])
    {
        if(empty($this->getId())){
            throw new Exception(sprintf("El id no puede estar vacio, se debe establecer el id de la plantilla en bd."));
        }
        $em = $this->container->get("doctrine")->getManager();
        $template = $em->find(Template::class,$this->getId());
        if(!$template){
            throw new Exception(sprintf("El id '%s' no se encontro en la bd, por favor verifique el id.",$this->getId()));
        }
        $path = $this->getDocumentPath($variables);
        $parameters = $template->getParametersArray();
        $parameters["force-download"] = false;
        $configurationManager = $this->container->get($this->container->getParameter("tecnocreaciones_tools.configuration_manager.name"));
        $variables["configurationManager"] = $configurationManager;
        $variables["container"] = $this->container;
        
        $this->getTemplateService()->compile($template, $path, $variables, $parameters);
        $this->fileName = $template->getName().".xls";

        return $path;
    }

    /**
     * @param TemplateService $templateService
     * @return \Maxtoan\ToolsBundle\Model\ObjectManager\ExporterManager\Model\ModelExcel
     */
    public function getTemplateService()
    {
        return $this->container->get(TemplateService::class);
    }

    /**
     * Traduce un indice
     * @param type $id
     * @param array $parameters
     * @param type $domain
     * @return type
     */
    protected function trans($id,array $parameters = array(), $domain = 'messages')
    {
        return $this->container->get('translator')->trans($id, $parameters, $domain);
    }
}