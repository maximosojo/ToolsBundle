<?php

namespace Maximosojo\ToolsBundle\Model;

/**
 * Modelo base estandar
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
abstract class ModelStandarMaster
{
	use \Maximosojo\ToolsBundle\Traits\IdentifiableTrait;
	use \Maximosojo\ToolsBundle\Traits\TimestampableUTCTrait;
	use \Maximosojo\ToolsBundle\Traits\SoftDeleteableTrait;
}