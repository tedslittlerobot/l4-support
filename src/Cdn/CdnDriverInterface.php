<?php namespace Tlr\Cdn;

use Symfony\Component\HttpFoundation\File;

interface CdnDriverInterface {

	/**
	 * Save the file passed through
	 * Process as neccessary
	 * Then return its name
	 *
	 * @param  File   $file
	 * @param  string $directory
	 * @return string              the saved file name
	 */
	public function save( $file, $overwrite = false );

	/**
	 * Take the given file
	 * Save it somewhere relevant
	 * Then return its name
	 *
	 * @param  File   $file
	 * @param  string $directory
	 * @return string              the saved file name
	 */
	public function writeFile( File $file, $name );

	/**
	 * The file that is passed
	 * Shall be removed forever.
	 * Nothing shall remain
	 *
	 * @param  string $filename
	 * @return boolean
	 */
	public function delete( $filename );

	/**
	 * Read the given file
	 * Basically, file_get_contents.
	 * Returns the contents
	 *
	 * @param  string $filename
	 * @return string              the file contents
	 */
	public function get( $filename  );

	/**
	 * A link shall be made
	 * To the item in question
	 * Returns null on fail
	 *
	 * @param  string $filename
	 * @return string
	 */
	public function url( $filename, $version = null );

}
