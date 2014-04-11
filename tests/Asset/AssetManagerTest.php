<?php

use Mockery as m;
use Tlr\Asset\AssetBlueprint;

class AssetManagerTest extends \PHPUnit_Framework_TestCase {

	public function setUp()
	{
		$this->manager = new Tlr\Asset\AssetManager;
	}

	public function testGlobals()
	{
		$this->assertSame( [], $this->manager->activeAssets() );

		$this->manager->register('foo', function(){}, false);
		$this->manager->register('bar', function(){}, true);
		$this->manager->register('baz', function(){}, true);

		$this->assertSame( ['bar', 'baz'], $this->manager->activeAssets() );
	}

	public function testRegisterSingleStack()
	{
		$func = function() {};
		$this->manager->register('foo', $func);

		$this->assertSame( [$func], $this->manager->getStack('foo') );

		$func2 = array( $this, __METHOD__ );
		$this->manager->register('bar', $func2);

		$this->assertSame( [$func2], $this->manager->getStack('bar') );

	}

	public function testRegisterMultiStack()
	{
		$func1 = function() {};
		$this->manager->register('foo', $func1);

		$this->assertSame( [$func1], $this->manager->getStack('foo') );

		$func2 = function() {};
		$this->manager->register('foo', $func2);

		$this->assertSame( [$func2, $func1], $this->manager->getStack('foo') );
	}

	public function testCompileAssetStack()
	{
		$bps = [];

		$func1 = function( AssetBlueprint $bp ) use ( &$bps ) { $bps[] = $bp; };
		$func2 = function( AssetBlueprint $bp ) use ( &$bps ) { $bps[] = $bp; };

		$this->manager->register('foo', $func1);
		$this->manager->register('foo', $func2);

		$asset = $this->manager->get('foo');

		$this->assertSame( $bps[0], $bps[1] );
		$this->assertSame( $bps[0], $asset );
	}

	public function testInvalidRegisterArgument()
	{
		$this->setExpectedException('InvalidArgumentException');

		$this->manager->register('foo', 'bar');
	}
}
