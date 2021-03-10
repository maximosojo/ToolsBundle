<?php

namespace Maxtoan\ToolsBundle\Model\ObjectManager\ExporterManager;

/**
 * Interfaz para plantillas
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
interface TemplateInterface
{    
    public function getTypeTemplate();
    
    public function getContent();

    public function setTypeTemplate($typeTemplate);

    public function setContent($content);

    public function getVariables();

    public function getParameters();

    public function setId($id);
}
