<?php

namespace Maximosojo\ToolsBundle\Service\DynamicBuilder\Widget;

use Limenius\Liform\Liform;
use JMS\Serializer\Annotation as JMS;
use Maximosojo\ToolsBundle\Service\DynamicBuilder\Widget\BaseWidget;
use Maximosojo\ToolsBundle\Service\DynamicBuilder\Traits\DataTrait;

/**
 * Widget para formularios
 *
 * @author Máximo Sojo <mxsojo13@gmail.com>
 * @JMS\ExclusionPolicy("ALL");
 */
class FormWidget extends BaseWidget
{
	/**
     * @var Liform 
     */
    private $liform;

    use DataTrait;

	public function __construct()
	{
        parent::__construct("form");
    }

    public function setLiform(Liform $liform)
    {
        $this->liform = $liform;
        return $this;
    }

    public function transform()
    {
    	$this->data = $this->liform->transform($this->data);
    }
}
