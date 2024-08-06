<?php

namespace Maximosojo\ToolsBundle\Component\Liform;

use Limenius\Liform\Transformer\AbstractTransformer as BaseAbstractTransformer;
use Limenius\Liform\Transformer\TransformerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeGuesserInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * AbstractTransformer
 *
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
abstract class AbstractTransformer extends BaseAbstractTransformer implements TransformerInterface
{
    public function __construct(TranslatorInterface $translator, FormTypeGuesserInterface $validatorGuesser = null)
    {
        $this->translator = $translator;
        $this->validatorGuesser = $validatorGuesser;
    }

    public function isRequired(FormInterface $form): bool
    {
        return $form->getConfig()->getOption('required')??false;
    }
}
