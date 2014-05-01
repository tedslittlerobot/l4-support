<?php namespace Tlr\Cdn;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Tlr\Asset\AssetManager
 */
class CdnFacade extends Facade {

	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor() { return 'cdn'; }

}
