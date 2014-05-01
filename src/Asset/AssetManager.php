<?php namespace Tlr\Asset;

class AssetManager {

	/**
	 * The asset generators
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
	protected $activeAssets = array();

	/**
	 * Activate assets.
	 * Can be done with arrays, or
	 * Variadically
	 *
	 * @param string|array $keys
	 */
	public function activate( $keys )
	{
		foreach ((array) $keys as $key)
		{
			if ( ! in_array($key, $this->activeAssets) )
			{
				$this->activeAssets[] = $key;
			}
		}

		return $this;
	}

	/**
	 * Register assets:
	 * Pass a key, and callable
	 * that will define it.
	 *
	 * @param  string  $key
	 * @param  callable  $assets
	 * @param  boolean $global
	 * @return $this
	 */
	public function register( $key, callable $assetGenerator, $activate = false )
	{
		if ( ! is_array( array_get($this->assets, $key) ) )
		{
			$this->assets[$key] = array();
		}

		array_unshift($this->assets[$key], $assetGenerator);

		if ( $activate )
		{
			$this->activate( $key );
		}

		return $this;
	}

	/**
	 * Resolve the assets.
	 * Only show javascript ones.
	 * Return the objects.
	 *
	 * @param  string $position the position of the js assets
	 * @return array
	 */
	public function getJS( $position = null )
	{
		$js = array();

		foreach ($this->resolve( $this->activeAssets() ) as $asset)
		{
			foreach ($asset->getJs($position) as $script)
			{
				$js[] = $script;
			}
		}

		return $js;
	}

	/**
	 * Resolve the assets.
	 * Only show CSS ones.
	 * Return the objects.

	 * @param  string $position the position of the css assets
	 * @return array
	 */
	public function getStyles()
	{
		$styles = array();

		foreach ($this->resolve( $this->activeAssets() ) as $asset)
		{
			foreach ($asset->getCss() as $script)
			{
				$styles[] = $script;
			}
		}

		return $styles;
	}

	/**
	 * For internal use.
	 * Get the definition stack
	 * For the given key
	 *
	 * @param  string $key
	 * @return array
	 */
	public function getStack($key)
	{
		return $this->assets[$key];
	}

	/**
	 * Get assets by key.
	 * Run Generator functions.
	 * Return a Blueprint.
	 *
	 * @param  string $key
	 * @return AssetBlueprint
	 */
	public function get($key)
	{
		return $this->compileAssetStack($key);
	}

	/**
	 * For internal use.
	 * Get the active assets list.
	 * (Returns only keys)
	 *
	 * @param  string $key
	 * @return AssetBlueprint
	 */
	public function activeAssets()
	{
		return $this->activeAssets;
	}

	/**
	 * @see get
	 * @param  string $key
	 * @return AssetBlueprint
	 */
	protected function compileAssetStack($key)
	{
		$asset = new AssetBlueprint;

		foreach ($this->getStack($key) as $callable)
		{
			call_user_func($callable, $asset);

			if ( $asset->overwrites() )
			{
				break;
			}
		}

		return $asset;
	}

	/**
	 * For internal use.
	 * Resolve some dependancies.
	 * Returns some Blueprints
	 *
	 * @param  string|array $keys
	 * @return array
	 */
	public function resolve( $keys )
	{
		$stack = array();

		foreach( (array)$keys as $key )
		{
			if ( isset($stack[$key]) )
			{
				continue;
			}

			$asset = $this->get($key);

			$stack[$key] = $asset;

			$stack = array_merge( $this->resolve($asset->requirements()), $stack );
		}

		return $stack;
	}
}
