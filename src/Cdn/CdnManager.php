<?php namespace Tlr\Cdn;

use Tlr\Support\Manager;

class CdnManager extends Manager {

	protected $configNamespace = 'cdn';

	public function createFileDriver( $config )
	{
		return new FileDriver( $this->app->make('Illuminate\Filesystem\Filesystem'), $config );
	}

}
