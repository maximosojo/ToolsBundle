<?php

namespace Maximosojo\ToolsBundle\Twig\Extension;

use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;
use Maximosojo\ToolsBundle\Service\OptionManager\OptionManagerInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * OptionManagerExtension
 * 
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
class OptionManagerExtension extends AbstractExtension 
{
    private $optionManager;

    public function __construct(OptionManagerInterface $optionManager)
    {
        $this->optionManager = $optionManager;
    }

    public function getName() 
    {
        return 'option_twig_extension';
    }

	public function getFunctions() 
    {
        return [            
            new TwigFunction('get_option_value', array($this, 'getOptionValue'),array('is_safe' => ['html']))
        ];
    }

    /**
     * Renderiza maximo por pagina
     *
     * @param   $key
     *
     * @return  string
     */
    public function getOptionValue($key, array $options = array())
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            "wrapper" => null,
            "default" => null
        ]);
        $options = $resolver->resolve($options);

        return $this->optionManager->get($key,$options["wrapper"],$options["default"]);
    }
}