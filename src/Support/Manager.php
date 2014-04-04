<?php namespace Tlr\Support\Manager;

abstract class Manager extends Illuminate\Support\Manager {

	/**
	 * The config namespace to use
	 * @var string
	 */
	protected $configNamespace;

	/**
	 * Get the default driver key
	 * @return string
	 */
	public function getDefaultDriver()
	{
		return $this->app['config'][$this->configNamespace . '.default'];
	}

	/**
	 * Get the config array for the given type
	 * @param  string $name
	 * @return array
	 */
	public function getDriverConfig( $name )
	{
		$this->app['config'][$this->configNamespace '.drivers.' . $name]
	}

	/**
	 * Create a new driver instance.
	 *
	 * @param  string  $driver
	 * @return mixed
	 *
	 * @throws \InvalidArgumentException
	 */
	protected function createDriver($name)
	{
		$config = $this->getDriverConfig($name);

		$driver = $config['driver'];

		$method = 'create'.ucfirst( $driver ).'Driver';

		// We'll check to see if a creator method exists for the given driver. If not we
		// will check for a custom driver creator, which allows developers to create
		// drivers using their own customized driver creator Closure to create it.
		if (isset($this->customCreators[$driver]))
		{
			return $this->callCustomCreator($driver, $config);
		}
		elseif (method_exists($this, $method))
		{
			return $this->$method( $config );
		}

		throw new \InvalidArgumentException("Driver [$driver] not supported.");
	}

	/**
	 * Call a custom driver creator.
	 *
	 * @param  string  $driver
	 * @return mixed
	 */
	protected function callCustomCreator( $driver, $config = array() )
	{
		return $this->customCreators[$driver]( $this->app, $config );
	}

}
