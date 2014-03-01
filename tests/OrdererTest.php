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

		$sorted = $this->orderer->insert( $array, 'c', 1 );

		$this->assertEquals( array('a', 'c', 'd', 'c'), $sorted );
	}

	public function testMoveBackwards()
	{
		$array = array(
			'a', 'c', 'd', 'e', 'b', 'f'
		);

		$sorted = $this->orderer->move( $array, 4, 1 );

		$this->assertSame( array('a', 'b', 'c', 'd', 'e', 'f'), $sorted );
	}

	public function testMoveForwards()
	{
		$array = array(
			'a', 'e', 'b', 'c', 'd', 'f'
		);

		$sorted = $this->orderer->move( $array, 1, 4 );

		$this->assertSame( array('a', 'b', 'c', 'd', 'e', 'f'), $sorted );
	}

	public function testMoveItem()
	{
		$array = array(
			'a', 'c', 'd', 'e', 'b', 'f'
		);

		$sorted = $this->orderer->moveItem( $array, 'b', 1 );

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
			(object)[ 'index' => 69 ],
			(object)[ 'index' => 70 ],
			(object)[ 'index' => 71 ],
		);

		$target = array(
			(object)[ 'index' => 0 ],
			(object)[ 'index' => 1 ],
			(object)[ 'index' => 2 ],
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
			[ 'index' => 69 ],
			[ 'index' => 70 ],
			[ 'index' => 71 ],
		);

		$target = array(
			[ 'index' => 0 ],
			[ 'index' => 1 ],
			[ 'index' => 2 ],
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
