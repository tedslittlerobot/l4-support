<?php namespace Tlr\Cdn;

use Tlr\Support\Manager;

/**
 * Needs Redo.
 */
class CdnManager {

	/**
	 * The application instance.
	 *
	 * @var \Illuminate\Foundation\Application
	 */
	protected $app;

	/**
	 * The creatable locations
	 * @var array
	 */
	protected $config = array();

	/**
	 * The registered custom location creators.
	 *
	 * @var array
	 */
	protected $customDrivers = array();

	/**
	 * The array of created "locations".
	 *
	 * @var array
	 */
	protected $locations = array();

	/**
	 * Get the default locaiton
	 * @var array
	 */
	protected $defaultLocation = array();

	/**
	 * Create a new manager instance.
	 *
	 * @param  \Illuminate\Foundation\Application  $app
	 * @return void
	 */
	public function __construct($app)
	{
		$this->app = $app;
	}

	/**
	 * Get a location instance.
	 *
	 * @param  string  $location
	 * @return mixed
	 */
	public function location($location = null)
	{
		$location = $location ?: $this->getDefaultLocation();

		// If the given location has not been created before, we will create the instances
		// here and cache it so we can return it next time very quickly. If their is
		// already a location created by this name, we'll just return that instance.
		if ( ! isset($this->locations[$location]))
		{
			$this->locations[$location] = $this->createLocation($location);
		}

		return $this->locations[$location];
	}

	public function getDefaultLocation()
	{
		return $this->defaultLocation;
	}

	public function setDefaultLocation( $key )
	{
		$this->defaultLocation = $key;
		return $this;
	}

	/**
	 * Create a new location instance.
	 *
	 * @param  string  $location
	 * @return mixed
	 *
	 * @throws \InvalidArgumentException
	 */
	protected function createLocation($location)
	{
		$config = $this->config[$location];

		$method = 'create'.ucfirst( $config['driver'] ).'Driver';

		// We'll check to see if a creator method exists for the given location. If not we
		// will check for a custom location creator, which allows developers to create
		// locations using their own customized location creator Closure to create it.
		if (isset($this->customDrivers[ $config['driver'] ]))
		{
			return call_user_func_array($this->customDrivers[$config['driver']], array($this->app, $config));
		}
		elseif (method_exists($this, $method))
		{
			return call_user_func_array(array($this, $method), array($config));
		}

		throw new \InvalidArgumentException("Location [$location] not defined.");
	}

	/**
	 * Register a custom location creator Closure.
	 *
	 * @param  string   $location
	 * @param  Closure  $callback
	 * @return \Illuminate\Support\Manager|static
	 */
	public function extend($location, $callback)
	{
		// @TODO: assert $callback is_callable

		$this->customDrivers[$location] = $callback;

		return $this;
	}

	public function getConfig()
	{
		return $this->config;
	}

	public function getCustomDrivers()
	{
		return $this->customDrivers;
	}

	public function addLocation($name, $config)
	{
		$this->config[$name] = $config;

		return $this;
	}

	/**
	 * Get all of the created "locations".
	 *
	 * @return array
	 */
	public function getLocations()
	{
		return $this->locations;
	}

	/**
	 * Dynamically call the default location instance.
	 *
	 * @param  string  $method
	 * @param  array   $parameters
	 * @return mixed
	 */
	public function __call($method, $parameters)
	{
		return call_user_func_array(array($this->location(), $method), $parameters);
	}

	public function createFileDriver( $config )
	{
		return new FileDriver( $this->app->make('files'), $config );
	}

}
