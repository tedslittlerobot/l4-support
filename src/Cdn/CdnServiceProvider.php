<?php namespace Tlr\Cdn;

use Illuminate\Support\ServiceProvider;

class CdnServiceProvider extends ServiceProvider {

	/**
	 * @inheritdoc
	 */
	protected $defer = false;

	/**
	 * @inheritdoc
	 */
	public function register()
	{
		$this->app->bindShared('cdn', function()
		{
			$cdn = new CdnManager($this->app);

			foreach ($this->app['config']->get('cdn.locations') as $key => $location)
			{
				$cdn->addLocation($key, $location);
			}

			return $cdn;
		});
	}

	/**
	 * @inheritdoc
	 */
	public function provides()
	{
		return array('cdn');
	}
}
