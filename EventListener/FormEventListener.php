<?php

/*
 * This file is part of the Máximo Sojo - maxtoan package.
 * 
 * (c) https://maxtoan.github.io/
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maxtoan\ToolsBundle\EventListener;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Captura los eventos de los formularios
 *
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
class FormEventListener extends AbstractTypeExtension
{
    private $container;
    public function __construct(ContainerInterface $container){
        $this->container = $container;
    }
    
    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function () use ($options) {
          $em = $this->container->get('doctrine')->getManager();
          if(!$em->getFilters()->isEnabled("enableable")){
            $em->getFilters()->enable("enableable");
            $em->getFilters()->getFilter("enableable")->disableForEntity($options["data_class"]);
          }  
        });

        $builder->addEventListener(FormEvents::POST_SUBMIT, function () { 
          $em = $this->container->get('doctrine')->getManager();
          if($em->getFilters()->isEnabled("enableable")){
            $em->getFilters()->disable("enableable");
          } 
        });
    }

    /**
     * @inheritdoc
     */
    public function getExtendedType()
    {
        return FormType::class;
    }
}