<?php namespace Tlr\Asset;

use Illuminate\Support\ServiceProvider;

class AssetServiceProvider extends ServiceProvider {

	/**
	 * @inheritdoc
	 */
	protected $defer = true;

	public function boot()
	{
		$this->app['router']->filter('asset', function($route, $request, $asset)
		{
			$this->app['assets']->activate( $asset );
		});
	}

	/**
	 * @inheritdoc
	 */
	public function register()
	{
		$this->app->bindShared('assets', function()
		{
			return new AssetManager;
		});
	}

	/**
	 * @inheritdoc
	 */
	public function provides()
	{
		return array('assets');
	}

}
