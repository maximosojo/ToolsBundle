<?php

namespace Maximosojo\ToolsBundle\Model\Core\View\Options;

/**
 * Row
 * 
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
class Row
{
    /**
     * @var array
     */
    private $cols;

    /**
     * Row constructor.
     */
    public function __construct()
    {
        $this->cols = [];
    }

    /**
     * @return cols
     */
    public function getCols()
    {
        return $this->cols;
    }

    /**
     * @param cols $cols
     */
    public function setCols($cols)
    {
        $this->cols = $cols;
        
        return $this;
    }

    /**
     * @param cols $cols
     */
    public function addCol(Col $col)
    {
        $this->cols[] = $col;
        
        return $this;
    }
}
