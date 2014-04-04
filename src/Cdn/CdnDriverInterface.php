<?php namespace Tlr\Cdn;

use Symfony\Component\HttpFoundation\File;

interface CdnDriverInterface {

	/**
	 * Take the given file
	 * Save it somewhere relevant
	 * Then return its name
	 *
	 * @param  File   $file
	 * @param  string $directory
	 * @return string              the saved file name
	 */
	public function save( File $file, $namespace = '', $overwrite = false );

	/**
	 * The file that is passed
	 * Shall be removed forever.
	 * Nothing shall remain
	 *
	 * @param  string $filename
	 * @param  string $namespace
	 * @return boolean
	 */
	public function delete( $filename, $namespace = '' );

	/**
	 * Read the given file
	 * Basically, file_get_contents.
	 * Returns the contents
	 *
	 * @param  string $filename
	 * @param  string $namespace
	 * @return string              the file contents
	 */
	public function get( $filename, $namespace = '' );

	/**
	 * It takes the two strings,
	 * Making a versioned filename,
	 * Then it returns that.
	 *
	 * @param  string $filename
	 * @param  string $version
	 * @return string
	 */
	public function filename( $filename, $version = null );

	/**
	 * A link shall be made
	 * To the item in question
	 * Returns null on fail
	 *
	 * is not linkable
	 * @param  string $filename
	 * @param  string $namespace
	 * @return string
	 */
	public function url( $filename, $namespace = '' );

}
