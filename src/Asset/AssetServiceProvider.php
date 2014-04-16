<?php namespace Tlr\Asset;

use Illuminate\Support\ServiceProvider;

class AssetServiceProvider extends ServiceProvider {

	/**
	 * @inheritdoc
	 */
	protected $defer = true;

	public function boot()
	{
		$this->package( 'tlr/l4-support', 'tlr-asset' );

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
		$this->app->register('Tlr\Support\SupportServiceProvider');

		$this->app->bindShared('assets', function()
		{
			$assetManager = new AssetManager;

			/////  PREDEFINED LIBRARIES  /////

			$assetManager->register('jquery', function($asset)
			{
				$asset->js('//ajax.googleapis.com/ajax/libs/jquery/{version}/jquery.min.js', array('version' => '1.11.0'));
			});
			$assetManager->register('jquery-ui', function($asset)
			{
				$asset->js('//ajax.googleapis.com/ajax/libs/jqueryui/{version}/jquery-ui.min.js', array('version' => '1.10.4'));
				$asset->css('//ajax.googleapis.com/ajax/libs/jqueryui/{version}/themes/smoothness/jquery-ui.css', array('version' => '1.10.4'));
				$asset->requires('jquery');
			});
			$assetManager->register('angular-js', function($asset)
			{
				$asset->js('//ajax.googleapis.com/ajax/libs/angularjs/{version}/angular.min.js', array('version' => '1.2.15'));
			});
			$assetManager->register('bootstrap', function($asset)
			{
				$asset->js('//netdna.bootstrapcdn.com/bootstrap/{version}/js/bootstrap.min.js', array('version' => '3.1.1'));
				$asset->css('//netdna.bootstrapcdn.com/bootstrap/{version}/css/bootstrap.min.css', array('version' => '3.1.1'));
			});
			$assetManager->register('gumby', function($asset)
			{
				$asset->js('//cdn.jsdelivr.net/gumby/{version}/css/gumby.css', array('version' => '2.5.11'));
			});
			$assetManager->register('semantic-ui', function($asset)
			{
				$asset->css('//cdnjs.cloudflare.com/ajax/libs/semantic-ui/{version}/css/semantic.min.css', array('version' => '0.13.0'));
			});

			return $assetManager;
		});
	}

	/**
	 * @inheritdoc
	 */
	public function provides()
	{
		return array('assets');
	}

	public function package($package, $namespace = null, $path = null)
	{
		$namespace = $this->getPackageNamespace($package, $namespace);

		$path = $path ?: $this->guessPackagePath();

		$view = $path . 'resources/views/' . str_replace('tlr-', '', $namespace);

		if ($this->app['files']->isDirectory($view))
		{
			$this->app['view']->addNamespace($namespace, $view);
		}
	}
}
