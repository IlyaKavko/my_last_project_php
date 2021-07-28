<?php
namespace Autocall\UnitTest;

class AmoHockTest extends \PHPUnit\Framework\TestCase
{
	/**
	 * @dataProvider engine
	 */

	public function testCreateRequest():void
	{
		$this->assertTrue(true);
	}

	static function engine(): array {
		return [[new AmoHockTest()]];
	}
}