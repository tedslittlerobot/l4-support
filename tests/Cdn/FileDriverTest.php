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

		$this->assertEquals('path:public/foo/bar/baz/woop', $this->manager->path(['bar/baz', 'woop']));
		$this->assertEquals('path:public/foo/bar/baz/boom', $this->manager->path('bar/baz', 'boom'));
	}

	public function testGenerateCustomBasePath()
	{
		$this->constructor([ 'base' => 'woop' ]);

		$this->assertEquals('woop/tmp/bar/baz', $this->manager->path('bar/baz'));
	}

	public function testParseFileString()
	{
		$this->constructor();

		$this->assertInstanceOf('Symfony\Component\HttpFoundation\File\File', $this->manager->parseFile( __FILE__ ));
	}

	public function testParseFileInstance()
	{
		$this->constructor();

		$file = m::mock('Symfony\Component\HttpFoundation\File\File');
		$this->assertSame($file, $this->manager->parseFile( $file ));
	}

	public function testParseFileInfo()
	{
		$this->constructor();

		// test string assignment
		$this->assertInstanceOf('Symfony\Component\HttpFoundation\File\File', $this->manager->parseFile( new \SplFileInfo( __FILE__ ) ));
	}

	public function testFilename()
	{
		$this->constructor();

		$file = m::mock('Symfony\Component\HttpFoundation\File\File');

		$file->shouldReceive('guessExtension')->andReturn('foo');

		$this->assertRegExp('/[a-z0-9]*\.foo/', $this->manager->filename($file));
	}

	public function testVersionname()
	{
		$this->constructor();

		$this->assertEquals('foo.bar.baz', $this->manager->versionname('foo.baz', 'bar'));
		$this->assertEquals('foo.bar', $this->manager->versionname('foo', 'bar'));
	}
}