<?php namespace Tlr\Cdn;

use Tlr\Support\Manager;

class CdnManager extends Manager {

	public function createFileDriver( $config )
	{
		return new FileDriver( $this->app->make('files'), $config );
	}

}
