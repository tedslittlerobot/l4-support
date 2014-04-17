<?php namespace Tlr\Cdn\FileName;

use Tlr\Support\Manager;

/**
 * Needs Redo.
 */
class FileNameManager extends Manager {

	public function createMd5Driver( $config )
	{
		return new Md5FileNameGenerator( $config );
	}

	public function createRawDriver( $config )
	{
		return new RawFileNameGenerator( $config );
	}

	public function createTimestampDriver( $config )
	{
		return new TimestampFileNameGenerator( $config );
	}

}
