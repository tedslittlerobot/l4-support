<?php namespace Tlr\Asset;

class AssetBlueprint {

	/**
	 * The js array
	 * @var array
	 */
	protected $js = array();

	/**
	 * The css array
	 * @var array
	 */
	protected $css = array();

	/**
	 * The requirements array
	 * @var array
	 */
	protected $requirements = array();

	/**
	 * Does this blueprint overwrite its parents
	 * @var array
	 */
	protected $overwrites = false;

	/**
	 * Require assets.
	 * Supply variadically,
	 * Or as an array.
	 *
	 * @param  string|array $requirements
	 * @return $this
	 */
	public function requires( $requirements )
	{
		if ( ! is_array($requirements) )
		{
			$requirements = func_get_args();
		}

		$this->requirements = array_merge( $this->requirements, $requirements );

		return $this;
	}

	/**
	 * For internal use.
	 * List all the other assets
	 * This asset requires.
	 *
	 * @return array
	 */
	public function requirements()
	{
		return $this->requirements;
	}

	/**
	 * Add an asset file.
	 * A URL is needed.
	 * Options are extra.
	 *
	 * @param  string $url
	 * @param  array  $options
	 * @param  array  $attributes
	 * @return $this
	 */
	public function css( $url, $options = array(), $attributes = array() )
	{
		$this->css[$url] = (object)array(
			'url' => $url,
			'options' => $options,
			'attributes' => $attributes
		);

		return $this;
	}

	/**
	 * Get the CSS
	 * All files linked to this asset
	 * Shall be returned
	 *
	 * @return array
	 */
	public function getCss()
	{
		return $this->css;
	}

	/**
	 * Add an asset file.
	 * A URL is needed.
	 * Options are extra.
	 *
	 * @param  string $url
	 * @param  array  $options
	 * @param  array  $attributes
	 * @return $this
	 */
	public function js( $url, $options = array(), $attributes = array() )
	{
		$this->js[$url] = (object)array(
			'url' => $url,
			'options' => $options,
			'attributes' => $attributes
		);

		return $this;
	}

	/**
	 * Get the Javascript
	 * All files linked to this asset
	 * Shall be returned
	 *
	 * @return array
	 */
	public function getJs( $position = null )
	{
		$js = $this->js;

		if (!is_null($position))
		{
			$js = array_filter($js, function($item) use ($position)
			{
				return dot_get($item, 'options.position') == $position;
			});
		}

		return $js;
	}

	/**
	 * Set whether or not this asset overwrites parents
	 * @param  boolean $overwrites
	 * @return $this
	 */
	public function overwrite( $overwrites = true )
	{
		$this->overwrites = $overwrites;

		return $this;
	}

	/**
	 * For internal use.
	 * Make this asset override
	 * All that went before.
	 *
	 * @return boolean
	 */
	public function overwrites()
	{
		return $this->overwrites;
	}

	/**
	 * Bulk-set the version
	 * Of all files defined thus far
	 * To that which is giv'n
	 *
	 * @param  string $version
	 * @return $this
	 */
	public function version( $version )
	{
		foreach ($this->js as $script)
		{
			$script->options['version'] = $version;
		}

		foreach ($this->css as $script)
		{
			$script->options['version'] = $version;
		}

		return $this;
	}
}
