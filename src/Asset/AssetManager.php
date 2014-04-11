<?php namespace Tlr\Asset;

class AssetManager {

	/**
	 * The assets
	 *
	 * This is a 2-dimentional array - so that each asset
	 * can be a stack of assets
	 * @var array
	 */
	protected $assets = array();

	/**
	 * A list of global keys
	 * @var array
	 */
	protected $globals = array();

	/**
	 * Register a new asset
	 * @param  string  $key
	 * @param  callable  $assets
	 * @param  boolean $global
	 * @return $this
	 */
	public function register( $key, $assets, $global = false )
	{
		if (!is_callable($assets))
		{
			throw new \InvalidArgumentsException("The second argument to register must be a callable");
		}

		if ( ! is_array( array_get($this->assets, $key) ) )
		{
			$this->assets[$key] = array();
		}

		array_unshift($this->assets[$key], $assets);

		if ( $global && !in_array($key, $this->globals) )
		{
			$this->globals[] = $key;
		}

		return $this;
	}

	/**
	 * Get an array of JS assets
	 * @param  string $position the position of the js assets
	 * @return array
	 */
	public function getJS( $position = 'footer' ) { return array(); }

	/**
	 * Get an array of CSS assets
	 * @param  string $position the position of the css assets
	 * @return array
	 */
	public function getStyles() { return array(); }

	/**
	 * Get an asset stack by key
	 * @param  string $key
	 * @return array
	 */
	public function getStack($key)
	{
		return $this->assets[$key];
	}

	/**
	 * Get the given asset
	 * @param  string $key
	 * @return AssetBlueprint
	 */
	public function get($key)
	{
		return $this->compileAssetStack($key);
	}

	/**
	 * Compile the given asset stack
	 * @param  string $key
	 * @return AssetBlueprint
	 */
	protected function compileAssetStack($key)
	{
		$asset = new AssetBlueprint;

		foreach ($this->getStack[$key] as $callable)
		{
			call_user_func($callable, $asset);

			if ( $asset->overwrites() ) break;
		}

		return $asset;
	}
}
