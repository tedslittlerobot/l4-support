<?php namespace Tlr\Asset;

/**
 * @see \Illuminate\Events\Dispatcher
 */
class AssetFacade extends Facade {

	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor() { return 'assets'; }

}
