<?php

use Mockery as m;

class FunctionsTest extends \PHPUnit_Framework_TestCase {

	public function testDotGetArray()
	{
		$array = array(
			'one' => array(
				'two' => 'three'
			),
			'ein' => array(
				'swei' => 'drei'
			),
		);

		$this->assertEquals( 'three', dot_get( $array, 'one.two' ) );
		$this->assertEquals( array('swei' => 'drei'), dot_get( $array, 'ein' ) );
	}


	public function testDotGetObject()
	{
		$array = (object) array(
			'one' => (object)array(
				'two' => 'three'
			),
			'ein' => (object)array(
				'swei' => 'drei'
			),
		);

		$this->assertEquals( 'three', dot_get( $array, 'one.two' ) );
		$this->assertEquals( (object)array('swei' => 'drei'), dot_get( $array, 'ein' ) );
	}

	public function testDotGetFromUnset()
	{
		$array = (object) array(
			'one' => (object)array(
				'two' => 'three'
			),
			'ein' => array(
				'swei' => 'drei'
			),
		);

		$this->assertEquals( 'default', dot_get( $array, 'one.four.ten', 'default' ) );
		$this->assertEquals( 'default', dot_get( $array, 'ein.fier.zehn', 'default' ) );
	}

	public function testDotGetFromWrongType()
	{
		$array = (object) array(
			'one' => 1
		);

		$this->assertEquals( 'default', dot_get( $array, 'one.two', 'default' ) );
	}

	public function testDotGetMixed()
	{
		$array = (object) array(
			'one' => array(
				'two' => 'three'
			),
			'ein' => array(
				'swei' => 'drei'
			),
		);

		$this->assertEquals( 'three', dot_get( $array, 'one.two' ) );
		$this->assertEquals( array('swei' => 'drei'), dot_get( $array, 'ein' ) );
	}

	public function testArrayFind()
	{
		$foo = array(
			'one' => (object)array( 'two' => 'three' ),
		);
		$bar = (object)array(
			'one' => array( 'two' => 'drei' ),
		);
		$array = array(
			'foo' => $foo,
			'bar' => $bar
		);

		$this->assertEquals( $foo, array_find_dot( 'three', $array, 'one.two' ) );
		$this->assertEquals( $bar, array_find_dot( 'drei', $array, 'one.two' ) );
	}

	public function testArrayFindFailiure()
	{
		$foo = array(
			'one' => (object)array( 'two' => 'three' ),
		);
		$bar = (object)array(
			'one' => array( 'two' => 'drei' ),
		);
		$array = array(
			'foo' => $foo,
			'bar' => $bar
		);

		$this->assertEquals( 'default', array_find_dot( 'three', $array, 'one.two.three', 'default' ) );
	}

	public function testArraySpliceValidItem()
	{
		$array = array( 'one', 'two', 'three' );

		$item = array_splice_item( $array, 'two' );

		$this->assertEquals('two', $item);
		$this->assertSame(['one', 'three'], $array);
	}

	public function testArraySpliceInvalidItem()
	{
		$array = array( 'one', 'two', 'three' );

		$item = array_splice_item( $array, 'four' );

		$this->assertNull( $item );
		$this->assertSame(['one', 'two', 'three'], $array);
	}

}
