<?php

/*
 * This file is part of the TecnoCreaciones package.
 * 
 * (c) www.tecnocreaciones.com
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maximosojo\ToolsBundle\Model\SequenceGenerator;

use LogicException;
use Doctrine\Common\Util\ClassUtils;
use Maximosojo\ToolsBundle\Interfaces\SequenceGenerator\ItemReferenceInterface;
use Maximosojo\ToolsBundle\Interfaces\SequenceGenerator\SequenceGeneratorInterface;

/**
 * Base del generador de secuencias
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
abstract class SequenceGenerator implements SequenceGeneratorInterface
{
    /**
     * Instancia del generador de secuencias
     * @var \Maximosojo\ToolsBundle\Service\SequenceGenerator\SequenceGeneratorService
     */
    protected $sequenceGenerator;
    
    /**
     * Construye la referencia por defecto
     * @param ItemReferenceInterface $item
     * @param array $config
     * @return type
     */
    public function buildRef(ItemReferenceInterface $item,array $config) 
    {
        $mask = $config['mask'];
        $className = $config['className'];
        $field = $config['field'];
        $qb = $this->sequenceGenerator->createQueryBuilder();
        $qb->from($className,'p');
        
        return $this->sequenceGenerator->generateNext($qb, $mask,$field,[],$config);
    }
    
    /**
     * Establece la referencia aun objeto
     * @param ItemReferenceInterface $item
     * @return type
     * @throws LogicException
     */
    public function setRef(ItemReferenceInterface $item) 
    {
        $className = ClassUtils::getRealClass(get_class($item));
        
        $classMap = $this->getClassMap();
        if(!isset($classMap[$className])){
            throw new LogicException(sprintf("No ha definido la configuracion de '%s' para generar su referencia",$className));
        }
        $resolver = new \Symfony\Component\OptionsResolver\OptionsResolver();
        $resolver->setDefined([
            "method","field","options","mask"
        ]);
        $resolver->setDefaults([
            'method' => 'buildRef',
            'field' => 'ref',
            'use_cache' => false,
//            'options' => array()
        ]);
        
        $config = $resolver->resolve($classMap[$className]);
        $config['className'] = $className;
        $method = $config['method'];
        $ref = $this->$method($item,$config);
        $item->setRef($ref);
        return $ref;
    }
    
    /**
     * Establece el generador de secuencia
     * @param \Maximosojo\ToolsBundle\Service\SequenceGenerator\SequenceGeneratorService $sequenceGenerator
     */
    function setSequenceGenerator(\Maximosojo\ToolsBundle\Service\SequenceGenerator\SequenceGeneratorService $sequenceGenerator) 
    {
        $this->sequenceGenerator = $sequenceGenerator;
    }
}
