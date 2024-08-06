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
use Maximosojo\ToolsBundle\Component\Liform\AbstractTransformer;

/**
 * @author Nacho Martín <nacho@limenius.com>
 */
class BooleanTransformer extends AbstractTransformer
{
    use \Maximosojo\ToolsBundle\Component\Liform\CommonFunctionsTrait;
    
    /**
     * {@inheritdoc}
     */
    public function transform(FormInterface $form, array $extensions = [], ?string $widget = null): array
    {
        $this->initCommonCustom($form);
        $schema = ['type' => 'boolean'];
        $schema = $this->addCommonSpecs($form, $schema, $extensions, $widget);
        $emptyData = $form->getConfig()->getOption('empty_data');
        if(($emptyData instanceof \Closure) === false){
            $schema["empty_data"] = $emptyData;
        }

        $schema = $this->addData($form, $schema);
        $schema = $this->addCommonCustom($form, $schema);
        
        return $schema;
    }
}
