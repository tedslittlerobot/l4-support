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
	// protected $manipulations = array();

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
	public function filename( $filename, $version = null )
	{
		$components = explode($filename, '.');

		$extension = array_pop($components);

		if (count($components) > 1)
		{
			array_pop($components);
		}

		$filename = Str::slug( implode('.', $components) );

		if ( is_string( $version ) )
		{
			$filename = "{$filename}.{$version}";
		}

		$filename = "{$filename}.{$extension}";

		return $filename;
	}

	/**
	 * @inheritdoc
	 */
	public function save( File $file, $namespace = '', $overwrite = false )
	{
		// @TODO: get filename
		$filename = '';

		// @TODO: use SplFileInfo, rather than SymfonyFile
		Input::file('photo')->move( $this->path() );

		return $filename;
	}

	/**
	 * @inheritdoc
	 */
	public function delete( $filename, $namespace = '' )
	{
		return $this->files->delete( $this->path( $this->filename( $filename, $namespace ) ) );
	}

	/**
	 * @inheritdoc
	 */
	public function get( $filename, $namespace = '' )
	{
		return $this->files->get( $this->path( $this->filename( $filename, $namespace ) ) );
	}

	/**
	 * @inheritdoc
	 */
	public function url( $filename, $namespace = '' )
	{
		return URL::to( $this->path( $this->filename( $filename, $namespace ) ) );
	}

}
