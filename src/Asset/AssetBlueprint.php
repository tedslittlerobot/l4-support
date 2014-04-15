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
	 * Add one or many requirements
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
	 * Get the blueprint's requirements
	 * @return array
	 */
	public function requirements()
	{
		return $this->requirements;
	}

	/**
	 * Add a CSS file to the asset blueprint
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
			'attributes' => $attributes,
		);

		return $this;
	}

	/**
	 * Get the blueprint's getCss
	 * @return array
	 */
	public function getCss()
	{
		return $this->css;
	}

	/**
	 * Add a javascript file to the asset blueprint
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
			'attributes' => $attributes,
		);

		return $this;
	}

	/**
	 * Get the blueprint's getJS
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
	 * Determine if this asset should overwrite its parents
	 * @return boolean
	 */
	public function overwrites()
	{
		return $this->overwrites;
	}

	/**
	 * Update the version tag of all asset files
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
