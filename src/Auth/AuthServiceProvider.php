<?php namespace Tlr\Auth;

use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->commands('Tlr\Auth\UserMakeCommand');

		$this->app->register('Tlr\Support\EventRoutingServiceProvider');
	}

	/**
	 * Set up some boot actions
	 */
	public function boot()
	{
		$this->package('tlr/l4-auth');

		$this->userEvents( $this->app['events'] );

		$events->listen('routes.start', array($this, 'routes'));
		$events->listen('routes.admin-prefix', array($this, 'adminPrefixRoutes'));
		$events->listen('routes.public', array($this, 'publicRoutes'));
		$events->listen('routes.login-prefix', array($this, 'loginPrefixRoutes'));
		$events->listen('routes.login', array($this, 'loginRoutes'));
		$events->listen('routes.private', array($this, 'privateRoutes'));
		$events->listen('routes.admin', array($this, 'adminRoutes'));

		$this->filters( $this->app['router'] );

		$this->bindings();
	}

	public function bindings()
	{
		$this->app['router']->bind('user_id', function( $id )
		{
			return User::findOrFail( $id );
		});
	}

	/**
	 * Some User events
	 * @param  Illuminate\Events\Dispatcher $event
	 * @todo: move this somewhere more relevant
	 */
	public function userEvents( $event )
	{
		$event->listen('auth.login', function($user, $remember)
		{
			$user->last_login = new Carbon;
			$user->save();
		});
	}

	/**
	 * Set up some filters
	 * @param  Router $router
	 */
	public function filters( $router )
	{
		$router->filter('auth', function()
		{
			if (\Auth::guest())
			{
				return \Redirect::guest( route( 'login' ) );
			}
		});
	}

	public function routes( $router, $events )
	{
		$router->group( [ 'before' => 'guest' ], function () use ( $router, $events )
		{
			$events->fire('routes.public', array( $router, $events ));
		} );

		$router->group( ['before' => 'auth'], function() use ( $router, $events )
		{
			$events->fire('routes.private', array( $router, $events ));

			$events->fire('routes.admin-prefix', array( $router, $events ));
		} );
	}


	public function adminPrefixRoutes($router, $events)
	{
		$router->group( ['prefix' => 'admin'], function() use ( $router, $events )
		{
			$events->fire('routes.admin', array( $router, $events ));
		} );
	}

	public function publicRoutes($router, $events)
	{
		$events->fire('routes.login-prefix', array( $router, $events ) );
	}

	public function loginPrefixRoutes($router, $events)
	{
		$events->fire('routes.login', array( $router, $events ));
	}

	public function loginRoutes($router, $events)
	{
		$router->get('log/me/in', [ 'as' => 'login', 'uses' => 'Tlr\Auth\LoginController@loginForm' ]);
		$router->post('i/am/important', [ 'as' => 'login.attempt', 'uses' => 'Tlr\Auth\LoginController@login', 'before' => 'csrf' ]);

		// PASSWORD RESET

		$router->get('reset/password/request', [ 'as' => 'password.request', 'uses' => 'Tlr\Auth\PasswordResetController@request' ]);
		$router->post('reset/password/request', [ 'as' => 'password.request.process', 'uses' => 'Tlr\Auth\PasswordResetController@processRequest' ]);

		$router->get('reset/password/{token}', [ 'as' => 'password.reset', 'uses' => 'Tlr\Auth\PasswordResetController@reset' ]);
		$router->post('reset/password', [ 'as' => 'password.reset.process', 'uses' => 'Tlr\Auth\PasswordResetController@processReset' ]);
	}

	public function privateRoutes($router, $events)
	{
		$router->any('logout', [ 'as' => 'logout', 'uses' => 'Tlr\Auth\LoginController@logout' ]);
	}

	public function adminRoutes($router, $events)
	{
		$router->group(['before' => 'can:manage_users'], function() use ( $router )
		{
			$router->get('users', [ 'as' => 'admin.user.index', 'uses' => 'Tlr\Auth\UsersController@index' ]);
			$router->get('users/{user_id}', [ 'as' => 'admin.user', 'uses' => 'Tlr\Auth\UsersController@show' ]);
			$router->get('users/{user_id}/edit', [ 'as' => 'admin.user.edit', 'uses' => 'Tlr\Auth\UsersController@edit' ]);
			$router->put('users/{user_id}/edit', [ 'as' => 'admin.user.update', 'uses' => 'Tlr\Auth\UsersController@update' ]);
		});

		$router->get('profile', [ 'as' => 'admin.profile', 'uses' => 'Tlr\Auth\UsersController@profile' ]);
		$router->get('profile/edit', [ 'as' => 'admin.profile.edit', 'uses' => 'Tlr\Auth\UsersController@editProfile' ]);
		$router->put('profile/edit', [ 'as' => 'admin.profile.update', 'uses' => 'Tlr\Auth\UsersController@updateProfile' ]);
	}


	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

	/**
	 * Override method for more shallow file structure
	 * @inheritdoc
	 */
	public function guessPackagePath()
	{
		$path = with(new \ReflectionClass($this))->getFileName();

		return realpath(dirname($path).'/../');
	}

}
