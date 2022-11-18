<?php

namespace Maximosojo\ToolsBundle\Service\DynamicBuilder\Widget;

use Limenius\Liform\Liform;
use JMS\Serializer\Annotation as JMS;

/**
 * Widget para formularios
 *
 * @author Máximo Sojo <mxsojo13@gmail.com>
 * @JMS\ExclusionPolicy("ALL");
 */
class FormWidget extends BaseWidget
{
	/**
	 * @JMS\Expose
     * @JMS\SerializedName("data")
	 */
	protected $data;

	/**
     * @var Liform 
     */
    private $liform;

	public function __construct()
	{
        parent::__construct("form");
    }

    public function setData($data)
    {
    	$this->data = $data;

    	return $this;
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
