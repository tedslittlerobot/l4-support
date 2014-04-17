<?php

interface FileNameGeneratorInterface {

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

}
