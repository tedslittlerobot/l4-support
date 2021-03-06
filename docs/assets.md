Asset Management
================

> Managing Assets

> Need not be labourious

> When you think it through

### Setup

- Add the service provider: `Tlr\Asset\AssetServiceProvider`
- Add the facade alias: `'Asset' => 'Tlr\Asset\AssetFacade'`

### Register assets

```php
// Register jQuery
Asset::register('jquery', function( AssetBlueprint $asset )
{
	// add a js file
	$asset->js('//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js');
});

// Register jQuery UI
Asset::register('jquery-ui', function( $asset )
{
	// add a js file
	$asset->js('//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js');
	// add a css file
	$asset->css('//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css');

	// require the jQuery asset
	$asset->requires('jquery');
});

// Register a script for some ui based admin panels
Asset::register('admin-panels', function( $asset )
{
	// add a js file
	$asset->js('/js/admin-panels.js');

	// require jquery ui
	// The requires method can take an array of multiple requirements, or
	// a list of requirements. It can be called multiple times to add
	// dependancies
	$asset->requires('jquery-ui');
});
```

### Activate

In you code, specify what assets you want to use:

```php
Asset::activate('admin-panels');
```

Or define it as a route filter:

```php
Route::group(['prefix' => 'admin', 'filter' => 'auth|asset:admin-panels']);
```

### Render

Now in your header, get your css files:

```php
@asset-styles
```

and your js files for your footer:

```php
@asset-js
```

### Versions

If you use the above blade tags, you can also add a version to a js or css asset that will be subbed into the url - for example the built-in jquery declaration looks similar to this:

```php
Asset::register('jquery', function( AssetBlueprint $asset )
{
	// added a version to the "options" array as the second argument
	$asset->js( '//ajax.googleapis.com/ajax/libs/jquery/{version}/jquery.min.js', ['version' => '1.11.0'] );
});
```

### Overriding

You can override a defined asset - if we assume jquery has been defined as above:

```php
Asset::register('jquery', function( AssetBlueprint $asset )
{
	// override the version of previously defined tags
	$asset->version( '2.1.0' );

	// add another js file to this asset (probably wouldn't do this on the jquery definition...!)
	// note that this won't have the version string defined above
	$asset->js( '/local/js/plugin.js' );
});
```

If you want to *completely overwrite* an asset, you can use the `overwrite` method. In the following example, the above two jquery definitions won't have any effect - jquery will only load the single new js file:

```php
Asset::register('jquery', function( AssetBlueprint $asset )
{
	$asset->js('/my/own/jquery.js');

	// Specify that this asset definition won't extend the previously defined ones
	$asset->overwrite();
});
```

### Predefined Assets

The following assets are defined already:

Key                                      | JS  | CSS | Version
-----------------------------------------|-----|-----|---------
[jquery](http://jquery.com/)             | yay | nay | 1.11.0
[jquery-ui](http://jqueryui.com/)        | yay | yay | 1.10.4
[angular](http://angularjs.org/)         | yay | nay | 1.2.15
[bootstrap](http://getbootstrap.com/)    | yay | yay | 3.1.1
[gumby](http://gumbyframework.com/)      | nay | yay | 2.5.11
[semantic-ui](http://semantic-ui.com/)   | nay | yay | 0.13.0
