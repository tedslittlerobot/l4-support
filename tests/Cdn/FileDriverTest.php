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

		$this->driver = new Tlr\Cdn\FileDriver($this->app, $this->files, $config);
	}

	public function tearDown()
	{
		m::close();
	}

	public function testPath()
	{
		$this->constructor([ 'path' => 'foo' ]);

		$this->assertEquals('path:public/foo', $this->driver->path());

		$this->assertEquals('path:public/foo/bar/baz', $this->driver->path('bar/baz'));

		$this->assertEquals('path:public/foo/bar/baz/woop', $this->driver->path(['bar/baz', 'woop']));
		$this->assertEquals('path:public/foo/bar/baz/boom', $this->driver->path('bar/baz', 'boom'));
	}

	public function testGenerateCustomBasePath()
	{
		$this->constructor([ 'base' => 'woop' ]);

		$this->assertEquals('woop/tmp/bar/baz', $this->driver->path('bar/baz'));
	}

	public function testParseFileString()
	{
		$this->constructor();

		$this->assertInstanceOf('Symfony\Component\HttpFoundation\File\File', $this->driver->parseFile( __FILE__ ));
	}

	public function testParseFileInstance()
	{
		$this->constructor();

		$file = m::mock('Symfony\Component\HttpFoundation\File\File');
		$this->assertSame($file, $this->driver->parseFile( $file ));
	}

	public function testParseFileInfo()
	{
		$this->constructor();

		// test string assignment
		$this->assertInstanceOf('Symfony\Component\HttpFoundation\File\File', $this->driver->parseFile( new \SplFileInfo( __FILE__ ) ));
	}

	public function testFilename()
	{
		$this->constructor();

		$file = m::mock('Symfony\Component\HttpFoundation\File\File');

		$file->shouldReceive('guessExtension')->andReturn('foo');

		$this->assertRegExp('/[a-z0-9]*\.foo/', $this->driver->filename($file));
	}

	public function testVersionname()
	{
		$this->constructor();

		$this->assertEquals('foo.bar.baz', $this->driver->versionname('foo.baz', 'bar'));
		$this->assertEquals('foo.bar', $this->driver->versionname('foo', 'bar'));
	}

	public function testDelete()
	{
		$this->constructor(['path' => 'foo']);

		$this->files->shouldReceive('delete')->once()->with('path:public/foo/bar')->andReturn('baz');

		$this->assertEquals('baz', $this->driver->delete('bar'));
	}

	public function testGet()
	{
		$this->constructor(['path' => 'foo']);

		$this->files->shouldReceive('get')->once()->with('path:public/foo/bar')->andReturn('baz');

		$this->assertEquals('baz', $this->driver->get('bar'));
	}


	public function testUrlGenerator()
	{
		$this->constructor(['path' => 'foo']);

		\Illuminate\Support\Facades\URL::shouldReceive('to')->once()->with('foo/bar')->andReturn('baz');

		$this->assertEquals('baz', $this->driver->url('bar'));
	}


}
