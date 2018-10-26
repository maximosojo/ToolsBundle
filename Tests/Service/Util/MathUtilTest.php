<?php


namespace Atechnologies\ToolsBundle\Tests\Service\Util;

use Atechnologies\ToolsBundle\Service\Util\MathUtil;

class MathUtilTest extends \PHPUnit_Framework_TestCase
{
	public function testSum()
	{
		$a = 1;
		$b = 3;
		$sum = MathUtil::sum($a,$b);
		$this->assertEquals(4, $sum);
	}
}