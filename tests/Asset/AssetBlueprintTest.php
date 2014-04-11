<?php

use Mockery as m;

class AssetBlueprintTest extends \PHPUnit_Framework_TestCase {

	public function setUp()
	{
		$this->blueprint = new Tlr\Asset\AssetBlueprint;
	}

	public function testOverwrites()
	{
		$this->assertFalse( $this->blueprint->overwrites() );

		$this->blueprint->overwrite();

		$this->assertTrue( $this->blueprint->overwrites() );
	}

	public function testRequirements()
	{
		$this->assertSame( [], $this->blueprint->requirements() );

		$this->blueprint->requires( 'foo' );
		$this->blueprint->requires( 'bar' );
		$this->blueprint->requires( [ 'baz', 'woop' ] );

		$this->assertSame( ['foo', 'bar', 'baz', 'woop'], $this->blueprint->requirements() );
	}
}
