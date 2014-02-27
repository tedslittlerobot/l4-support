<?php

use Mockery as m;

class SlugifierTest extends \PHPUnit_Framework_TestCase {

	public function setUp()
	{
		$this->slugifier = new Tlr\Support\Slugifier;
	}

	public function testNumericIncrementor()
	{
		$foo = 'foo';
		$result = $this->slugifier->numericIncrementor($foo, 1);
		$this->assertEquals('foo-1', $result);
	}

	public function testGetDefaultIncrementor()
	{
		$result = $this->slugifier->getIncrementor(null);
		$this->assertEquals( array($this->slugifier, 'numericIncrementor') , $result);
	}

	public function testGetIncrementor()
	{
		$result = $this->slugifier->getIncrementor('foobar');
		$this->assertEquals('foobar', $result);
	}

	public function testIncrementalSlug()
	{
		$input = 'Woop Woop';
		$xi = 0;
		$comparator = function($string) use (&$xi)
		{
			$xi++;
			return $string === 'woop-woop-4';
		};

		$result = $this->slugifier->incrementalSlug($input, $comparator);

		$this->assertEquals('woop-woop-4', $result);
		$this->assertEquals(5, $xi);
	}

	public function testDefaultIncrementalSlug()
	{
		$input = 'Woop Woop';
		$xi = 0;
		$comparator = function($string) use (&$xi)
		{
			$xi++;
			return true;
		};

		$result = $this->slugifier->incrementalSlug($input, $comparator);

		$this->assertEquals('woop-woop', $result);
		$this->assertEquals(1, $xi);
	}
}
