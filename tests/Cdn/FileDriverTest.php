<?php

use Mockery as m;

class FileDriverTest extends \PHPUnit_Framework_TestCase {

	public function setUp()
	{
		$this->app = m::mock('app');
		$this->files = m::mock('Illuminate\Filesystem\Filesystem');
	}

	public function constructor( $config = array() )
	{
		if ( in_array(array_get($config, 'base', 'public'), ['public', 'storage']) )
		{
			$this->app->shouldReceive('make')->with('path.' . array_get($config, 'base', 'public'))->once()->andReturn('path:' . array_get($config, 'base', 'public'));
		}

		$this->manager = new Tlr\Cdn\FileDriver($this->app, $this->files, $config);
	}

	public function tearDown()
	{
		m::close();
	}

	public function testPath()
	{
		$this->constructor([ 'path' => 'foo' ]);

		$this->assertEquals('path:public/foo', $this->manager->path());

		$this->assertEquals('path:public/foo/bar/baz', $this->manager->path('bar/baz'));
	}
}
