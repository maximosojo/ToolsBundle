<?php

namespace Maximosojo\ToolsBundle\Component\Liform\Transformer;

use Symfony\Component\Form\FormInterface;
use Limenius\Liform\Transformer\StringTransformer as AbstractStringTransformer;

/**
 * Transforma un boton
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class ButtonTransformer extends AbstractStringTransformer
{
    use \Maximosojo\ToolsBundle\Component\Liform\CommonFunctionsTrait;
    
    /**
     * {@inheritdoc}
     */
    public function transform(FormInterface $form, array $extensions = [], $widget = null): array
    {
        $schema = ['type' => 'string'];
        $schema = $this->addCommonSpecs($form, $schema, $extensions, $widget);
        $schema["render_in"] = $form->getConfig()->getOption('render_in');
        $schema["text_color"] = $form->getConfig()->getOption('text_color');
        $schema["background_color"] = $form->getConfig()->getOption('background_color');
        return $schema;
    }
}
