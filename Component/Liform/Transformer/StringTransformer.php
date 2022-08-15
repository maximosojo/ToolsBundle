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
use Limenius\Liform\Transformer\StringTransformer as AbstractStringTransformer;

/**
 * @author Nacho Martín <nacho@limenius.com>
 */
class StringTransformer extends AbstractStringTransformer
{
    use \Maximosojo\ToolsBundle\Component\Liform\CommonFunctionsTrait;
    
    /**
     * {@inheritdoc}
     */
    public function transform(FormInterface $form, array $extensions = [], $widget = null)
    {
        $this->initCommonCustom($form);
        $schema = ['type' => 'string'];
        $schema = $this->addCommonSpecs($form, $schema, $extensions, $widget);
        $schema = $this->addMaxLength($form, $schema);
        $schema = $this->addMinLength($form, $schema);
        $schema = $this->addHelp($form, $schema);
        $schema = $this->addEmptyData($form, $schema);
        $schema = $this->addCommonCustom($form, $schema);
        $schema = $this->addData($form, $schema);
        $this->addWidget($form, $schema, false);

        return $schema;
    }

    /**
     * @param FormInterface $form
     * @param array         $schema
     *
     * @return array
     */
    protected function addEmptyData(FormInterface $form, array $schema)
    {
        $schema['empty_data'] = null;
    	if ($emptyData = $form->getConfig()->getOption('empty_data')) {
            if($emptyData instanceof \Closure){
                $emptyData = $emptyData($form);
            }
            $schema['empty_data'] = $emptyData;
        }

        return $schema;
    }

    /**
     * @param FormInterface $form
     * @param array         $schema
     * @param mixed         $configWidget
     *
     * @return array
     */
    protected function addWidget(FormInterface $form, array $schema, $configWidget)
    {
        $schema = parent::addWidget($form, $schema, $configWidget);
        if ($attr = $form->getConfig()->getOption('attr')) {
            if (isset($attr['widget'])) {
                $schema['widget'] = $attr['widget'];
            }
        }
        return $schema;
    }
}
