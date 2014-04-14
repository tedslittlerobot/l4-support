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
	 * Add a global key
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
	 * Register a new asset
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
	 * Get an array of JS assets
	 * @param  string $position the position of the js assets
	 * @return array
	 */
	public function getJS( $position = 'footer' )
	{
		$js = array();

		foreach ($this->resolve( $this->activeAssets() ) as $asset)
		{
			// dd( $asset->getJs() );
			foreach ($asset->getJs($position) as $script)
			{
				$js[] = $script;
			}
		}

		return $js;
	}

	/**
	 * Get an array of CSS assets
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
	 * Get the global asset keys
	 * @param  string $key
	 * @return AssetBlueprint
	 */
	public function activeAssets()
	{
		return $this->activeAssets;
	}

	/**
	 * Compile the given asset stack
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
	 * Resolve the given dependancies
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
