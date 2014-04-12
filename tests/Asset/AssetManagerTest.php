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

	public function testRegisterMultiStackOverwrites()
	{
		$func1 = function( AssetBlueprint $bp ) { $bp->require('bar'); };
		$this->manager->register('foo', $func1);

		$this->assertSame( [$func1], $this->manager->getStack('foo') );

		$func2 = function( AssetBlueprint $bp ) { $bp->overwrite(); };
		$this->manager->register('foo', $func2);

		$blueprint = $this->manager->get('foo');

		$this->assertSame( [], $blueprint->requirements() );
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
		$this->setExpectedException('Exception');

		$this->manager->register('foo', 'bar');
	}

	public function testResolveLinearRequirements()
	{
		$bps = [];

		$this->manager->register('foo',
			function( AssetBlueprint $bp ) use ( &$bps ) {
				$bp->js('javascript');
				$bp->requires('bar');
				$bps['foo'] = $bp;
			}
		);

		$this->manager->register('bar',
			function( AssetBlueprint $bp ) use ( &$bps ) {
				$bp->js('javascript');
				$bps['bar'] = $bp;
			}
		);

		$results = $this->manager->resolve('foo');

		$expected = array(
			'bar' => $bps['bar'],
			'foo' => $bps['foo'],
		);

		$this->assertSame( $expected, $results );
	}

	public function testGetCss()
	{
		$bps = [];

		$this->manager->register('foo',
			function( AssetBlueprint $bp ) use ( &$bps )
			{
				$bp->js('javascript');
				$bps['foo'] = $bp;
			}
		);

		$this->manager->register('bar',
			function( AssetBlueprint $bp ) use ( &$bps )
			{
				$bp->css('css');
				$bps['bar'] = $bp;
			}
		);

		$this->manager->activate(['foo', 'bar']);

		$results = $this->manager->getStyles();

		$expected = (object)array(
			'url' => 'css',
			'options' => array(),
			'attributes' => array(),
		);

		$this->assertEquals( [$expected], $results );
	}

	public function testGetJs()
	{
		$bps = [];

		$this->manager->register('foo',
			function( AssetBlueprint $bp ) use ( &$bps )
			{
				$bp->js('javascript');
				$bps['foo'] = $bp;
			}
		);

		$this->manager->register('bar',
			function( AssetBlueprint $bp ) use ( &$bps )
			{
				$bp->css('css');
				$bps['bar'] = $bp;
			}
		);

		$this->manager->activate(['foo', 'bar']);

		$results = $this->manager->getJs(null);

		$expected = (object)array(
			'url' => 'javascript',
			'options' => array(),
			'attributes' => array(),
		);

		$this->assertEquals( [$expected], $results );
	}

	public function testResolveSkipsResolvedAssets()
	{
		$bps = [];

		$this->manager->register('foo',
			function( AssetBlueprint $bp ) use ( &$bps ) {
				$bps[] = $bp;
			}
		);

		$results = $this->manager->resolve(['foo', 'foo']);

		$expected = array(
			'foo' => $bps[0],
		);

		$this->assertEquals( 1, count($bps) );
		$this->assertSame( $expected, $results );
	}
}
