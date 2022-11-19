<?php

/*
 * This file is part of the Maximosojo Tools package.
 * 
 * (c) https://maximosojo.github.io/tools-bundle
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maximosojo\ToolsBundle\Traits\Component;

use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * TranslatorTrait
 * 
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
trait TranslatorTrait
{
    protected $translator;

    /**
     * Traducción
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @param  String
     * @param  array
     * @param  string
     * @return Translation
     */
    public function trans($id,array $parameters = array(), $domain = "messages")
    {
        return $this->translator->trans($id, $parameters, $domain);
    }
    
    /**
     * setTranslator
     *  
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @param  TranslatorInterface $translator
     * @return TranslatorInterface
     * @required
     */
    public function setTranslator(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }
}