<?php

namespace Maximosojo\ToolsBundle\Model\Core\View;

use Maximosojo\ToolsBundle\Model\Core\View\Options\Row;

/**
 * Body
 * 
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
class Body
{
    /**
     * Text to display for row.
     *
     * Default: no row
     *
     * @var arra
     */
    public $rows;

    /**
     * @return Row
     */
    public function getRow()
    {
        $row = new Row();

        return $row;
    }

    public function getRows()
    {
        return $this->rows;
    }

    public function addRow(Row $row)
    {
        $this->rows[] = $row;

        return $this;
    }
}