<?php

namespace Maximosojo\ToolsBundle\Model;

/**
 * Modelo base estandar
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
abstract class ModelStandarMaster
{
	use \Maximosojo\ToolsBundle\Traits\ORM\IdentifiableTrait;
	use \Maximosojo\ToolsBundle\Traits\ORM\TimestampableUTCTrait;
	use \Maximosojo\ToolsBundle\Traits\ORM\SoftDeleteableTrait;
}