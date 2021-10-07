<?php

namespace Maxtoan\ToolsBundle\Model\Core\View;

use Maxtoan\ToolsBundle\Model\Core\View\Options\Row;

/**
 * Footer
 * 
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
class Footer
{
    /**
     * Text to display for row.
     *
     * Default: no row
     *
     * @var string
     */
    public $row;

    /**
     * View constructor.
     */
    public function __construct()
    {
        $this->row = new Row();
    }

    /**
     * @return Row
     */
    public function getRow()
    {
        return $this->row;
    }
}