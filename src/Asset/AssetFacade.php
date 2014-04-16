<?php namespace Tlr\Asset;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Tlr\Asset\AssetManager
 */
class AssetFacade extends Facade {

	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor() { return 'assets'; }

}
