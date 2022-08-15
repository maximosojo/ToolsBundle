<?php

namespace Maximosojo\ToolsBundle\Model\Core\View;

/**
 * Header
 * 
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
class Header
{
    /**
     * Text to display for title.
     *
     * Default: no title
     *
     * @var string
     */
    public $title;

    /**
     * Text to display for title.
     *
     * Default: no title
     *
     * @var string
     */
    public $subtitle;

    /**
     * @return Title
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Title
     */
    public function setSubTitle($subtitle)
    {
        $this->subtitle = $subtitle;

        return $this;
    }
}