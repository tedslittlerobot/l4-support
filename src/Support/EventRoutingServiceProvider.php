<?php namespace Tlr\Support;

use Illuminate\Support\ServiceProvider;

class EventRoutingServiceProvider extends ServiceProvider {

	/**
	 * Routing with event
	 * Both begins and finishes
	 * Within this method
	 *
	 * @return void
	 */
	public function boot()
	{
		$startRouter = function()
		{
			$this->app['events']->fire('routes.start', array( $this->app['router'], $this->app['events'] ));
			$this->app['events']->fire('routes.finish', array( $this->app['router'], $this->app['events'] ));
		};

		$this->app['events']->listen('artisan.start', $startRouter);
		$this->app['events']->listen('router.before', $startRouter);
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register() {}

}
