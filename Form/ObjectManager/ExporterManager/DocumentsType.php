<?php

namespace Maxtoan\ToolsBundle\Form\ObjectManager\ExporterManager;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * Formulario para generar documento
 *
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
class DocumentsType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $data = $builder->getData();
        $builder
                ->add('name', ChoiceType::class, [
                    'label' => ' ',
                    "choices" => $data
                ]
        );
    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver 
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'method' => 'POST'
        ));
    }

    public function getBlockPrefix()
    {
        return "documents_generated";
    }
}
