<?php

/*
 * This file is part of the Limenius\Liform package.
 *
 * (c) Limenius <https://github.com/Limenius/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maximosojo\ToolsBundle\Component\Liform\Transformer;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\ChoiceList\View\ChoiceGroupView;
use Maximosojo\ToolsBundle\Component\Liform\AbstractTransformer;
use Symfony\Component\Form\FormView;

/**
 * @author Nacho Martín <nacho@limenius.com>
 */
class ChoiceTransformer extends AbstractTransformer
{
    use \Maximosojo\ToolsBundle\Component\Liform\CommonFunctionsTrait;
    
    /**
     * {@inheritdoc}
     */
    public function transform(FormInterface $form, array $extensions = [], ?string $widget = null): array
    {
        $this->initCommonCustom($form);
        $formView = $this->formView;
        $translationDomain = $form->getConfig()->getOption('translation_domain');
        $emptyData = $form->getConfig()->getOption('empty_data');
        
        $choices = [];
        $currentValue = isset($formView->vars["value"]) ? $formView->vars["value"] : null;
        if(empty($currentValue)){
            $currentValue = $emptyData;
        }

        foreach ($formView->vars['choices'] as $choiceView) {
            if ($choiceView instanceof ChoiceGroupView) {
                foreach ($choiceView->choices as $choiceItem) {
                    $choices[] = $this->buildChoice($choiceItem, $currentValue, $translationDomain);
                }
            } else {
                $choices[] = $this->buildChoice($choiceView, $currentValue, $translationDomain);
            }
        }

        if ($formView->vars['multiple']) {
            $schema = $this->transformMultiple($form, $choices);
        } else {
            $schema = $this->transformSingle($form, $choices);
        }

        $this->addWidget($form, $schema, false);
        $schema = $this->addCommonSpecs($form, $schema, $extensions, $widget);
        $schema = $this->addHelp($form, $schema);
        $schema = $this->addCommonCustom($form, $schema);
        $schema = $this->addEmptyData($form,$formView,$schema);
        $schema = $this->addData($form,$schema);
        
        return $schema;
    }

    private function transformSingle(FormInterface $form, $choices)
    {
        $formView = $form->createView();

        $schema = [
            'choices' => $choices,
            'type' => 'string',
        ];

        if ($formView->vars['expanded']) {
            $schema['widget'] = 'choice-expanded';
        }

        return $schema;
    }

    private function transformMultiple(FormInterface $form, $choices)
    {
        $formView = $form->createView();

        $schema = [
            'choices' => $choices,
            'type' => 'array'
        ];

        if ($formView->vars['expanded']) {
            $schema['widget'] = 'choice-multiple-expanded';
        }

        return $schema;
    }

    /**
     * @param FormInterface $form
     * @param array         $schema
     *
     * @return array
     */
    protected function addEmptyData(FormInterface $form,FormView $formView, array $schema)
    {
     	if (($emptyData = $form->getConfig()->getOption('empty_data')) && !empty($formView->vars["value"])) {
            $schema['empty_data'] = [
                "id" => $formView->vars["value"],
                "label" => (string)$emptyData,
            ];
        }

        return $schema;
    }

    /**
     * isDisabled
     *  
     * @param  $attr
     * @return boolean
     */
    protected function isDisabled($attr)
    {
        $disabled = null;
        if ($attr && isset($attr["disabled"])) {
            $disabled = $attr["disabled"];
        }
        
        return $disabled;
    }

    /**
     * Construye la opcion para json
     * @param type $choiceView
     * @param type $currentValue
     * @param type $translationDomain
     * @return type
     */
    private function buildChoice($choiceView, $currentValue, $translationDomain)
    {
        $selected = $currentValue != null && $currentValue === $choiceView->value;
        $choice = [
            "id" => $choiceView->value,
            "label" => $this->translator->trans($choiceView->label, [], $translationDomain),
            "selected" => $selected,
            "disabled" => $this->isDisabled($choiceView->attr)
        ];
        if(isset($choiceView->attr["extra_json_keys"]) && is_array($choiceView->attr["extra_json_keys"])){
            foreach ($choiceView->attr["extra_json_keys"] as $key => $value) {
                $choice[$key] = $value;
            }
            unset($choiceView->attr["extra_json_keys"]);
        }
        return $choice;
    }
}
