<?php

namespace Maxtoan\ToolsBundle\Model\Core\View;

use Maxtoan\ToolsBundle\Model\Core\View\Header;
use Maxtoan\ToolsBundle\Model\Core\View\Body;
use Maxtoan\ToolsBundle\Model\Core\View\Footer;

/**
 * View
 * 
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
class View
{
    /**
     * Text to display for header.
     *
     * Default: no header
     *
     * @var string
     */
    public $header;

    /**
     * @var Body
     */
    public $body;

    /**
     * @var Footer
     */
    public $footer;

    /**
     * View constructor.
     */
    public function __construct()
    {
        $this->header = new Header();
        $this->body = new Body();
        $this->footer = new Footer();
    }

    /**
     * @return Header
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * @return Body
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @return Footer
     */
    public function getFooter()
    {
        return $this->footer;
    }
}