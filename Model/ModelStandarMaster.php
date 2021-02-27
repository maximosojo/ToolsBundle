<?php

namespace Maxtoan\ToolsBundle\Model;

/**
 * Modelo base estandar
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
abstract class ModelStandarMaster
{
	use \Maxtoan\ToolsBundle\Traits\IdentifiableTrait;
	use \Maxtoan\ToolsBundle\Traits\TimestampableUTCTrait;
	use \Maxtoan\ToolsBundle\Traits\SoftDeleteableTrait;
}