<?php namespace Tlr\Asset;

use Illuminate\Support\ServiceProvider;

class AssetServiceProvider extends ServiceProvider {

	/**
	 * @inheritdoc
	 */
	protected $defer = true;

	public function boot()
	{
		$this->app['blade']->extend(function($view, $compiler)
		{
			foreach (array('styles', 'js', 'header-js') as $namespace)
			{
				$pattern = $compiler->createPlainMatcher( "asset-{$namespace}" );
				$view = preg_replace($pattern, "$1<?php echo View::make('tlr-asset::{$namespace}')->render(); ?>", $view);
			}

			return $view;
		});


		/**
		 * @todo: add variadic input
		 */
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
