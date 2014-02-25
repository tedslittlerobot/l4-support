<?php

use Mockery as m;

class OrdererTest extends \PHPUnit_Framework_TestCase {

	public function setUp()
	{
		$this->orderer = new Tlr\Support\Database\Orderer;
	}

	public function testInsert()
	{
		$array = array(
			'a', 'd', 'c',
		);

		$sorted = $this->orderer->insert( 'c', $array, 2 );

		$this->assertEquals( array('a', 'c', 'd', 'c'), $sorted );
	}

	public function testMoveBackwards()
	{
		$array = array(
			'a', 'c', 'd', 'e', 'b', 'f'
		);

		$sorted = $this->orderer->move( 5, $array, 2 );

		$this->assertSame( array('a', 'b', 'c', 'd', 'e', 'f'), $sorted );
	}

	public function testMoveForwards()
	{
		$array = array(
			'a', 'e', 'b', 'c', 'd', 'f'
		);

		$sorted = $this->orderer->move( 2, $array, 5 );

		$this->assertSame( array('a', 'b', 'c', 'd', 'e', 'f'), $sorted );
	}

	public function testMoveItem()
	{
		$array = array(
			'a', 'c', 'd', 'e', 'b', 'f'
		);

		$sorted = $this->orderer->moveItem( 'b', $array, 2 );

		$this->assertSame( array('a', 'b', 'c', 'd', 'e', 'f'), $sorted );
	}

	/**
	 * A basic functional test example.
	 *
	 * @return void
	 */
	public function testObjectIndexAssignment()
	{
		$array = array(
			(object)[ 'order' => 69 ],
			(object)[ 'order' => 70 ],
			(object)[ 'order' => 71 ],
		);

		$target = array(
			(object)[ 'order' => 1 ],
			(object)[ 'order' => 2 ],
			(object)[ 'order' => 3 ],
		);

		$sorted = $this->orderer->assignIndices( $array );

		$this->assertEquals( $target, $sorted );
	}


	/**
	 * A basic functional test example.
	 *
	 * @return void
	 */
	public function testArrayIndexAssignment()
	{
		$array = array(
			[ 'order' => 69 ],
			[ 'order' => 70 ],
			[ 'order' => 71 ],
		);

		$target = array(
			[ 'order' => 1 ],
			[ 'order' => 2 ],
			[ 'order' => 3 ],
		);

		$sorted = $this->orderer->assignIndices( $array );

		$this->assertEquals( $target, $sorted );
	}

	public function testBaseAssignment()
	{
		$this->orderer->setBase( 69 );

		$this->assertSame( 69, $this->orderer->getBase() );
	}

}
