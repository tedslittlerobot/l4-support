<?php namespace Tlr\Cdn;

use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File;

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

	/**
	 * The manipulations to apply to the files
	 * @var array
	 */
	protected $manipulations = array();

	public function __construct( Filesystem $files, $config = array() )
	{
		$this->files = $files;

		$this->basePath = $this->generateBasePath( array_get( $config, 'base' ), $app );
		$this->directory = array_get( $config, 'path' );
		$this->manipulations = array_get( $config, 'manipulations', array() );
	}

	/**
	 * Generate the base path from config
	 * @param  string $path
	 * @param  Illuminate\Foundation\Application $app
	 * @return string
	 */
	protected function generateBasePath($path, $app)
	{
		if (is_null($path))
		{
			return $app->make('path.public');
		}

		if ($path == 'storage')
		{
			return $app->make('path.storage');
		}

		if ($path == 'public')
		{
			return $app->make('path.public');
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
		return realpath( $this->basePath . '/' . $this->directory . '/' . $path );
	}

	/**
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
