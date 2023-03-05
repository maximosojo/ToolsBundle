<?php

/*
 * This file is part of the Maximosojo Tools package.
 * 
 * (c) https://maximosojo.github.io/tools-bundle
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maximosojo\ToolsBundle\Service\Tabs;

use Maximosojo\ToolsBundle\Model\Tab\Tab;
use Maximosojo\ToolsBundle\Model\Tab\TabContent;
use Symfony\Component\HttpFoundation\RequestStack;
use RuntimeException;
use Twig\Environment;
use Symfony\Component\HttpFoundation\Response;

/**
 * Nuevo servicio generador de tabs
 * 
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
class TabsManager implements TabsManagerInterface
{
    /**
     * @var array
     */
    private $options;

    /**
     * Parametros que necesita la vista al renderizarse
     * @var array
     */
    private $parametersToView = [];

    private $twig;

    private $requestStack;

    public function __construct(RequestStack $requestStack, Environment $twig)
    {
        $this->requestStack = $requestStack;
        $this->twig = $twig;
    }

    /**
     * Instancia nueva tabs
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @param  Class
     * @return Tab
     */
    public function createNew(array $options = [])
    {
        $request = $this->requestStack->getCurrentRequest();

        $this->tab = new Tab($options);
        $this->tab->setRequest($request);
        return $this->tab;
    }

    public function addTabContent(TabContent $tabContent)
    {
        $this->tab->addTabContent($tabContent);
    }

    /**
     * Retorna la tab listas
     * @return Tab
     */
    public function buildTab()
    {
        $tab = $this->tab;
        $tab->setParameters($this->parametersToView);
        $tab->resolveCurrentTab();
        //$this->tab = null;
        //$this->parametersToView = [];
        return $tab;
    }

    /**
     * Renderiza la tab actual
     * @return Response
     */
    public function render()
    {
        $tab = $this->tab;
        $resolveCurrentTab = $tab->resolveCurrentTab();

        $extractParameters = function($parameters, array $base) {
            if (is_callable($parameters)) {
                $parameters = $parameters($base);
                if (!is_array($parameters)) {
                    throw new RuntimeException(sprintf("tab->getViewParameters debe retornar un array pero retorno '%s'", gettype($parameters)));
                }
            }
            if (is_null($parameters)) {
                $parameters = [];
            }
            if (!is_array($parameters)) {
                throw new RuntimeException(sprintf("El parametro de retorno deberia ser un array pero retorno '%s'", gettype($parameters)));
            }
            return $parameters;
        };

        $parameters = $extractParameters($this->tab->getViewParameters(), []);
        $request = $this->requestStack->getCurrentRequest();

        //Renderizar parametros de la tab solo si se esta renderizando la tab y no el padre.
        // if (!empty($request->get(Tab::NAME_CURRENT_TAB))) {
            // var_dump($resolveCurrentTab);
            $parameters = array_merge($parameters, $extractParameters($resolveCurrentTab->getViewParameters(), $parameters));
        // }

        $parameters = array_merge($parameters, $this->parametersToView);
        $parameters["tab"] = $tab;

        $template = $this->tab->getTemplate($this->tab->getOption("default_template"));

        $view = $this->twig->render($template, $parameters);
        return new Response($view);
    }
}
