<?php

use Mockery as m;

class OrderEnforcerTest extends \PHPUnit_Framework_TestCase {

	public function setUp()
	{
		$this->orderer = m::mock('Tlr\Support\Orderer');
		$this->enforcer = new Tlr\Support\OrderEnforcer($this->orderer);
	}

	public function testEnforce()
	{
		$array = array();
		$array[] = $this->listItem(false, 1);
		$array[] = $this->listItem(true, 3);
		$array[] = $this->listItem(true, 5);
		$array[] = $this->listItem(true, 8);

		$return = $this->enforcer->enforce( $array );

		$this->assertSame($array, $return);

		foreach ($return as $key => $value)
		{
			$this->assertEquals( $key + 1, $value->index );
		}
	}

	public function listItem( $dirty = true, $index = 1 )
	{
		$item = m::mock();

		$item->shouldReceive('isDirty')->once()->with('index')->andReturn($dirty);

		if ($dirty)
		{
			$item->shouldReceive('save')->once();
		}

		$item->index = $index;

		return $item;
	}

}
