<?php

namespace Maximosojo\ToolsBundle\Component\Liform;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\GroupSequence;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Funciones comunes
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
trait CommonFunctionsTrait
{
    /**
     * @var ValidatorInterface
     */
    private $validator;
    protected $formView;

    protected function initCommonCustom(FormInterface $form)
    {
        $this->formView = $form->createView();
    }

    protected function addCommonCustom(FormInterface $form, array $schema)
    {
        $formView = $this->formView;
        $formRoot = $form->getRoot();

        $schema["full_name"] = $formView->vars["full_name"];
        $schema = $this->addConstraints($form, $schema, $formRoot);
        $schema = $this->addDateParams($form, $schema);
        $schema = $this->addCommonConfigOptions($form, $schema);
        $schema = $this->addFromAttr($form, $schema);

        return $schema;
    }

    /**
     * Añade opciones de configuracion extra en el parametro "attr" que es dinamico
     * @param FormInterface $form
     * @param array $schema
     * @return type
     */
    protected function addFromAttr(FormInterface $form, array $schema)
    {
        if ($attr = $form->getConfig()->getOption('attr')) {
            // Unset
            $options = [
                "icon"
            ];
            foreach ($options as $option) {
                if (isset($attr[$option])) {
                    $schema[$option] = $schema['attr'][$option];
                    unset($schema['attr'][$option]);
                }
            }

            // Transalate
            $translationDomain = $form->getConfig()->getOption('translation_domain');
            $options = [
                "placeholder"
            ];
            foreach ($options as $option) {
                if (isset($attr[$option])) {
                    $schema['attr'][$option] = $this->translator->trans($schema['attr'][$option], [], $translationDomain);
                }
            }

            if(isset($attr["extras"])){
                foreach ($attr["extras"] as $key => $extra) {
                    $schema[$key] = $extra;
                }
                unset($schema['attr']["extras"]);
            }

            if (count($schema['attr']) == 0) {
                unset($schema['attr']);
            }
        }

        return $schema;
    }

    protected function addDateParams(FormInterface $form, array $schema)
    {
        if ($form->getConfig()->hasOption("format_from_server")) {
            $schema["format_from_server"] = $form->getConfig()->getOption("format_from_server");
            $schema["format_to_server"] = $form->getConfig()->getOption("format_to_server");
        }
        return $schema;
    }

    /**
     * Opciones comunes a configurar en los tipos para no agregar uno por uno
     * @param FormInterface $form
     * @param array $schema
     * @return type
     */
    protected function addCommonConfigOptions(FormInterface $form, array $schema)
    {
        $options = ["mode"];
        foreach ($options as $option) {
            if ($form->getConfig()->hasOption($option)) {
                $schema[$option] = $form->getConfig()->getOption($option);
            }
        }
        return $schema;
    }

    /**
     * Añade las validaciones
     * @param FormInterface $form
     * @param array $schema
     * @return type
     */
    protected function addConstraints(FormInterface $form, array $schema, FormInterface $formRoot)
    {
        $propertyName = $form->getName();
        $data = $form->getConfig()->getDataClass();
        $ignoreClass = [File::class];
        if (empty($data) || in_array($data,$ignoreClass)) {
            $formIterate = $form;
             while($formIterate->getParent() !== null){
                $formIterate = $formIterate->getParent();
                if($formIterate){
                    $dataClass = $formIterate->getConfig()->getDataClass();
                    if(!empty($dataClass) && !in_array($dataClass,$ignoreClass)){
                        $data = $formIterate->getConfig()->getDataClass();
                        break;
                    }
                }
            }
            
        }
        
        $schema['constraints'] = [];
        if ($constraints = $form->getConfig()->getOption('constraints')) {
            
        } else {
            $groups = $this->getValidationGroups($form);

            if (!$groups || !$this->validator->hasMetadataFor($data)) {
                return $schema;
            }

            $metadata = $this->validator->getMetadataFor($data);

            if (isset($metadata->properties[$propertyName]) && ($property = $metadata->properties[$propertyName]) !== null && count($property->constraintsByGroup) > 0 && count($groups) > 0) {
                foreach ($groups as $group) {
                    if (isset($property->constraintsByGroup[$group])) {
                        foreach ($property->constraintsByGroup[$group] as $constraint) {
                            //Evitar duplicidad
                            if (!in_array($constraint, $constraints)) {
                                $constraints[] = $constraint;
                            }
                        }
                    }
                }
            }
        }
        $schema['constraints'] = $constraints;

        return $schema;
    }

    /**
     * Añade el atributo data
     * @author  Máximo Sojo <maxsojo13@gmail.com>
     * @param FormInterface $form
     * @param array $schema
     */
    protected function addData(FormInterface $form, array $schema)
    {
        $schema['data'] = null;
        
        if (isset($this->formView->vars["value"]) && ($value = $this->formView->vars["value"]) != null) {
            if (is_array($value)) {
                foreach ($value as $key => $v) {
                    $value = $key;
                    break;
                }
            }
            $schema['data'] = $value;
        }

        if ($data = $form->getConfig()->getOption('data')) {
            $schema['data'] = $data;
        }

        return $schema;
    }

    /**
     * Returns the validation groups of the given form.
     *
     * @return string|GroupSequence|(string|GroupSequence)[] The validation groups
     */
    private function getValidationGroups(FormInterface $form)
    {
        // Determine the clicked button of the complete form tree
        $clickedButton = null;

        if (method_exists($form, 'getClickedButton')) {
            $clickedButton = $form->getClickedButton();
        }

        if (null !== $clickedButton) {
            $groups = $clickedButton->getConfig()->getOption('validation_groups');

            if (null !== $groups) {
                return self::resolveValidationGroups($groups, $form);
            }
        }

        do {
            $groups = $form->getConfig()->getOption('validation_groups');

            if (null !== $groups) {
                return self::resolveValidationGroups($groups, $form);
            }

            if (isset($this->resolvedGroups[$form])) {
                return $this->resolvedGroups[$form];
            }

            $form = $form->getParent();
        } while (null !== $form);

        return [Constraint::DEFAULT_GROUP];
    }

    /**
     * Post-processes the validation groups option for a given form.
     *
     * @param string|GroupSequence|(string|GroupSequence)[]|callable $groups The validation groups
     *
     * @return GroupSequence|(string|GroupSequence)[] The validation groups
     */
    private static function resolveValidationGroups($groups, FormInterface $form)
    {
        if (!\is_string($groups) && \is_callable($groups)) {
            $groups = $groups($form);
        }

        if ($groups instanceof GroupSequence) {
            return $groups->groups;
        }

        return (array) $groups;
    }

    /**
     * @required
     * @param ValidatorInterface $validator
     * @return $this
     */
    public function setValidator(ValidatorInterface $validator)
    {
        $this->validator = $validator;
        return $this;
    }

    /**
     * Añadir help
     *  
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @param  FormInterface $form
     * @param  array         $schema
     */
    protected function addHelp(FormInterface $form, array $schema)
    {
        $translationDomain = $form->getConfig()->getOption('translation_domain');
        if ($attr = $form->getConfig()->getOption('attr')) {
            if (isset($attr['help'])) {
                $schema['attr']['help'] = $this->translator->trans($attr['help'], [], $translationDomain);
            }
        }

        return $schema;
    }

    /**
     * @param FormInterface $form
     * @param array         $schema
     *
     * @return array
     */
    protected function addLabel(FormInterface $form, array $schema): array
    {
        $translationDomain = $form->getConfig()->getOption('translation_domain');
        $label = $form->getConfig()->getOption('label');
        if ($label) {
            if ($label) {
                $schema['title'] = $this->translator->trans($label, [], $translationDomain);
            } else {
                $schema['title'] = $this->translator->trans($form->getName(), [], $translationDomain);
            }    
        }

        return $schema;
    }

    public function isRequired(FormInterface $form): bool
    {
        return $form->getConfig()->getOption('required')??false;
    }
}
