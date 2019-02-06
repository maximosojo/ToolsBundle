<?php

/*
 * This file is part of the Máximo Sojo - maxtoan package.
 * 
 * (c) https://maxtoan.github.io/
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Atechnologies\ToolsBundle\Tests\Service\Util;

use Atechnologies\ToolsBundle\Service\Util\MathUtil;

/**
 * Math Test
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
class MathUtilTest extends \PHPUnit_Framework_TestCase
{
	public function testSum()
	{
		$a = 1;
		$b = 3;
		$sum = MathUtil::sum($a,$b);
		$this->assertEquals(4, $sum);
	}

	public function testSub()
	{
		$a = 3;
		$b = 1;
		$sub = MathUtil::sub($a,$b);
		$this->assertEquals(2, $sub);
	}

	public function testDiv()
	{
		$a = 1;
		$b = 3;
		$div = MathUtil::div($a,$b);
		$this->assertEquals(0.33, $div);
	}

	public function testMul()
	{
		$a = 1;
		$b = 3;
		$mul = MathUtil::mul($a,$b);
		$this->assertEquals(3, $mul);
	}
}