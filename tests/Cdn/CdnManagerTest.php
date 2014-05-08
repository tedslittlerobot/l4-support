<?php

use Mockery as m;

class CdnManagerTest extends \PHPUnit_Framework_TestCase {

	public function setUp()
	{
		$this->app = m::mock();

		$this->manager = new Tlr\Cdn\CdnManager($this->app);
	}

	public function tearDown()
	{
		m::close();
	}

	public function testExtend()
	{
		$callable = array($this, 'setUp');

		$this->manager->extend( 'foo', $callable );

		$this->assertSame(array('foo' => $callable), $this->manager->getCustomDrivers());
	}

	public function testAddLocation()
	{
		$config = ['foo', 'bar'];

		$this->manager->addLocation( 'baz', $config );

		$this->assertSame(array('baz' => $config), $this->manager->getConfig());
	}

	public function testDefaultLocation()
	{
		$this->manager->setDefaultLocation( 'foo' );

		$this->assertSame('foo', $this->manager->getDefaultLocation());
	}

	public function testMakeFileDriver()
	{
		$config = array('foo', 'bar');

		$files = m::mock('Illuminate\Filesystem\Filesystem');

		$this->app->shouldReceive('make')->with('files')->once()->andReturn($files);
		$this->app->shouldReceive('make')->with('path.public')->once()->andReturn('foobar');

		$fileDriver = $this->manager->createFileDriver( $config );

		$this->assertInstanceOf( 'Tlr\Cdn\FileDriver', $fileDriver );
	}

	public function testCreateLocation()
	{
		$config = array('driver' => 'file');

		$files = m::mock('Illuminate\Filesystem\Filesystem');
		$this->app->shouldReceive('make')->with('files')->once()->andReturn($files);
		$this->app->shouldReceive('make')->with('path.public')->once()->andReturn('foobar');

		$this->manager->addLocation('foo', $config);

		$fileDriver = $this->manager->location('foo');

		$this->assertInstanceOf( 'Tlr\Cdn\FileDriver', $fileDriver );
		$this->assertContains( $fileDriver, $this->manager->getLocations() );
	}

	public function testCustomDriver()
	{
		$config = array('driver' => 'woop');

		$callable = function( $app, $config )
		{
			return new Tlr\Cdn\FileDriver( $app, $app->make('files'), $config );
		};

		$this->manager->extend( 'woop', $callable );

		$files = m::mock('Illuminate\Filesystem\Filesystem');
		$this->app->shouldReceive('make')->with('files')->once()->andReturn($files);
		$this->app->shouldReceive('make')->with('path.public')->once()->andReturn('foobar');

		$this->manager->addLocation('foo', $config);

		$fileDriver = $this->manager->location('foo');

		$this->assertInstanceOf( 'Tlr\Cdn\FileDriver', $fileDriver );
		$this->assertContains( $fileDriver, $this->manager->getLocations() );
	}

	public function testCacheLocations()
	{
		$config = array('driver' => 'file');

		$files = m::mock('Illuminate\Filesystem\Filesystem');
		$this->app->shouldReceive('make')->with('files')->times(2)->andReturn($files);
		$this->app->shouldReceive('make')->with('path.public')->times(2)->andReturn('foobar');

		$this->manager->addLocation('foo', $config);
		$this->manager->addLocation('bar', $config);

		$fileDriverFoo = $this->manager->location('foo');
		$fileDriverBar = $this->manager->location('bar');

		$this->assertContainsOnly( 'Tlr\Cdn\FileDriver', $this->manager->getLocations() );

		$this->assertTrue($fileDriverFoo !== $fileDriverBar);
		$this->assertSame( $fileDriverFoo, $this->manager->location('foo') );
	}

	public function testCreateInvalidLocation()
	{
		$this->setExpectedException('InvalidArgumentException');

		$this->manager->addLocation('foo', array('driver' => 'bar'));

		$this->manager->location('foo');
	}

	public function testDynamicDefaultLocationCall()
	{
		$driver = m::mock();
		$this->manager->addLocation('foo', array('driver' => 'bar'));
		$this->manager->extend('bar', function() use ($driver)
		{
			return $driver;
		});

		$this->manager->setDefaultLocation('foo');

		$result = $this->manager->location();

		$this->assertSame($driver, $result);

		$driver->shouldReceive('baz')->once()->andReturn('woop');

		$this->assertEquals( 'woop', $this->manager->baz() );
	}

}
