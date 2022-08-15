<?php

namespace Maximosojo\ToolsBundle\Service\ObjectManager\ExporterManager\TemplateService\Adapter;

use Doctrine\ORM\EntityManager;

/**
 * Adaptador de doctrine
 *
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
class DoctrineORMAdapter implements TemplateAdapterInterface
{
    /**
     * Nombre de la clase
     * @var string
     */
    private $className;

    /**
     * @var EntityManager
     */
    private $em;
    
    public function __construct($className,EntityManager $em)
    {
        $this->className = $className;
        $this->em = $em;
    }
    
    public function find($id)
    {
        return $this->em->find($this->className, $id);
    }
}
