<?php namespace Tlr\Cdn;

use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use SplFileInfo;

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
	 * @inheritdoc
	 */
	public function save( $file, $namespace = '', $overwrite = false )
	{
		/**
		 * - Get file - handle SplFileInfo, and string
		 * - Generate filename
		 * - Upload (ie. call writeFile)
		 * - Repeat for Manipulations
		 * - Return filename
		 */

		// @TODO: get filename
		$filename = '';

		return $filename;
	}

	/**
	 * @inheritdoc
	 */
	public function writeFile( File $file, $name )
	{
		/**
		 * - Eval Upload directory
		 * - Write file
		 */

		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function delete( $filename )
	{
		// @TODO: delete manipulations
		return $this->files->delete( $this->path( $this->filename( $filename, $namespace ) ) );
	}

	/**
	 * @inheritdoc
	 */
	public function get( $filename, $version = null )
	{
		return $this->files->get( $this->path( $this->filename( $filename, $namespace ) ) );
	}

	/**
	 * @inheritdoc
	 */
	public function url( $filename, $version = null )
	{
		return URL::to( $this->path( $this->filename( $filename, $namespace ) ) );
	}

}
