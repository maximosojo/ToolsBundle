<?php

namespace Maximosojo\ToolsBundle\Twig\Extension;

use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;
use Maximosojo\ToolsBundle\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * PaginatorExtension
 * 
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
class PaginatorExtension extends AbstractExtension 
{
    use ContainerAwareTrait;

    public function getName() 
    {
        return 'paginator_twig_extension';
    }

	public function getFunctions() 
    {
        return [            
            new TwigFunction('paginator_maxPerPage_render', array($this, 'paginatorMaxPerPageRender'),array('is_safe' => ['html'])),
            new TwigFunction('paginator_pagination_render', array($this, 'paginatorPaginationRender'),array('is_safe' => ['html'])),
            new TwigFunction('paginator_sortable_render', array($this, 'paginatorSortableRender'),array('is_safe' => ['html'])),
        ];
    }

    /**
     * Renderiza maximo por pagina
     *
     * @param   $key
     *
     * @return  View | Template
     */
    public function paginatorMaxPerPageRender($paginator)
    {
        $resolver = new OptionsResolver();
        $resolver->setRequired(["links","meta","data"]);
        $paginator = $resolver->resolve($paginator);

        return $this->render("@MaximosojoTools/paginator/maxPerPage.html.twig",[
            "paginator" => $paginator
        ]);
    }

    /**
     * Renderiza paginador
     *
     * @param   $key
     *
     * @return  View | Template
     */
    public function paginatorPaginationRender($paginator)
    {
        $resolver = new OptionsResolver();
        $resolver->setRequired(["links","meta","data"]);
        $paginator = $resolver->resolve($paginator);

        return $this->render("@MaximosojoTools/paginator/pagination.html.twig",[
            "paginator" => $paginator
        ]);
    }

    /**
     * Renderiza maximo por pagina
     *
     * @param   $key
     *
     * @return  View | Template
     */
    public function paginatorSortableRender($paginator, string $title, $key)
    {
        $resolver = new OptionsResolver();
        $resolver->setRequired(["links","meta","data"]);
        $paginator = $resolver->resolve($paginator);

        $direction = "desc";
        $request = $this->container->get('request_stack')->getCurrentRequest();
        foreach ($request->get('sorting', []) as $key => $value) {
            $direction = 'asc' === \strtolower($value) ? 'desc' : 'asc';
        }

        return $this->render("@MaximosojoTools/paginator/sortable.html.twig",[
            "paginator" => $paginator,
            "title" => $title,
            "key" => $key,
            "direction" => $direction
        ]);
    }

    /**
     * Renderiza vista
     *
     * @param   $template
     * @param   $parameters
     *
     * @return  View
     */
    private function render($template,$parameters)
    {
        return $this->container->get('twig')->render($template,$parameters);
    }
}