Events Routing
==============

### Events

To use events for routing, add `Tlr\Support\EventRoutingServiceProvider` to your `providers` array in `config/app.php`.

Then you can define routes in event blocks, like so:

```php
Event::listen('routes.start', function( $routes )
{
	$routes->get('/', function() { return 'hello world'; });
});
```

The object passed in is the `Illuminate\Routing\Router` object.

The advantage of doing this is that you can distribute route definitions around your application, or allow other people to tap into different points in your routing structure. See in this example:

```php
Event::listen('routes.start', function( $routes )
{
	$routes->group([ 'before' => 'auth', 'prefix' => 'admin' ], function() use ( $routes )
	{
		Event::fire( 'routes.admin', [ $routes ] );
	});
});

# Somewhere else...

Event::listen('routes.admin', function( $routes )
{
	$routes->get('debug-console', function() { return 'debug console - admins only!'; });
});

```

This provides an easy way for someone to make routes for an admin panel, in a modular way, without having to duplicate or guess the filtering and routing logic.

*( NB - the code in the example does not provide a very useful debug console ;) )*
