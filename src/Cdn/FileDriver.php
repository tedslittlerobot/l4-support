<?php namespace Tlr\Cdn;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use SplFileInfo;

/**
 * path => 'path/to/dir' # tmp
 * base => '/base/path' # public (can be public or storage to use laravel's path generators)
 *
 */
class FileDriver /*implements CdnDriverInterface*/ {

	/**
	 * The base path that the files get saved to
	 * @var string
	 */
	protected $basePath;

	/**
	 * The subdirectory or namespace to store files
	 * @var string
	 */
	protected $directory;

	public function __construct( $app, Filesystem $files, $config = array() )
	{
		$this->files = $files;

		$this->basePath = $this->generateBasePath( array_get( $config, 'base' ), $app );
		$this->directory = array_get( $config, 'path', 'tmp' );
	}

	/**
	 * Generate the base path from config
	 * @param  string $path
	 * @param  Illuminate\Foundation\Application $app
	 * @return string
	 */
	protected function generateBasePath($path, $app)
	{
		if ( is_null($path) ) { $path = 'public'; }

		if ( in_array($path, array('public', 'storage')) )
		{
			return $app->make( 'path.' . $path );
		}

		return $path;
	}

	/**
	 * A helper function to get the full path
	 * @param  string $path (optional) a path suffix
	 * @return string
	 */
	public function path( $path = '' )
	{
		$path = trim(path_compile( is_array($path) ? $path : func_get_args() ), '/');

		return $this->basePath . '/' . $this->directory . ($path ? '/' . $path : '');
	}

	/**
	 * Make a File object out of the given input
	 * @param  mixed  $file
	 * @return File
	 */
	public function parseFile($file)
	{
		// don't do anything if it's already a File
		if ( $file instanceof File ) return $file;

		if ( $file instanceof SplFileInfo )
		{
			// if it's a SplFileInto, make it a File
			$file = new File( $file->getRealPath() );
		}

		// Otherwise assume (for now) it's a string
		return new File( $file );
	}

	/**
	 * Generate a filename
	 *
	 * @todo add other file name generators
	 *
	 * @param  File   $file
	 * @return string
	 */
	public function filename( File $file )
	{
		return md5(microtime()) . '.' . $file->guessExtension();
	}

	public function versionname( $name, $namespace = null )
	{
		if (is_null($namespace)) return $name;

		$components = explode('.', $name);
		$extension = null;

		if (count($components) > 1)
		{
			$extension = array_pop($components);
		}

		$components[] = $namespace;

		if ( ! is_null($extension) )
		{
			$components[] = $extension;
		}

		return implode('.', $components);
	}

	public function subdirectory( $name )
	{
		return '';
	}

	/**
	 * @inheritdoc
	 * @todo Manipulations
	 */
	public function save( $file )
	{
		$file = $this->parseFile( $file );

		$filename = $this->filename( $file );

		$this->writeFile( $file, $filename );

		return $filename;
	}

	/**
	 * @inheritdoc
	 */
	public function writeFile( File $file, $name, $namespace = null )
	{
		/**
		 * - Eval Upload directory
		 * - Write file
		 */

		$path = $this->subdirectory( $name );

		$name = $this->versionname( $name, $namespace );

		if ( ! $this->files->isDirectory( $this->path($path) ) )
		{
			$this->files->makeDirectory( $this->path($path), 0777, true );
		}

		return $this->files->move($file->getRealPath(), $this->path( $path, $name ) );
	}

	/**
	 * @inheritdoc
	 */
	public function delete( $filename )
	{
		// @TODO: delete manipulations
		return $this->files->delete( $this->path( $this->versionname( $filename ) ) );
	}

	/**
	 * @inheritdoc
	 */
	public function get( $filename, $version = null )
	{
		return $this->files->get( $this->path( $this->versionname( $filename, $version ) ) );
	}

	/**
	 * @inheritdoc
	 */
	public function url( $filename, $version = null )
	{
		return URL::to( path_compile( $this->directory, $this->versionname( $filename, $version ) ) );
	}
}
