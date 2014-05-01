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
			return new CdnManager($this->app);
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
