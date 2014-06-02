CDN
===

A way to manage file uploads and image manipulations programmatically. It is configurable through laravel's config system, so you can use file storage for local development, and a CDN for production.

## Roadmap

There are two main things left to do in the Cdn class:
- Image manipulations
- Drivers for common CDNs (rackspace, amazon, etc.)

Once they are done, the drivers interface will be completed, and that should be it for the time being ;)

## Setup

Add `Tlr\Cdn\CdnServiceProvider` to the `app.providers` array, and `'Cdn' => 'Tlr\Cdn\CdnFacade'` to the `app.aliases` array.

## The Config File

Add a `cdn.php` config file to `app/config`. Here is a sample config file:

```php
<?php

return array(
	'default' => 'profile-pictures',

	'locations' => array(
		'profile-pictures' => array(
			'driver' => 'file',
			'path' => 'uploads/profiles',
		),
		'article-featured-images' => array(
			'driver' => 'file',
			'path' => 'uploads/featured',
		),
	),
);
```

## Usage

You can use the Cdn class like so:

```php

$filename = CDN::location('profile-pictures')
	->save( Input::file('image') );

```

The file passed to save can be a file path to an image (`string`), or an `SplFileInfo` or `Symfony\Component\HttpFoundation\File\File` object, so you can pass the output of `Input::file('field-name')` straight to it.

To get the url to the file, pass the file name to the `url` method:

```php
<a href="{{ Cdn::location('profile-pictures')->url($filename) }}"></a>
```

And to delete the file:

```php
$success = CDN::location('profile-pictures')
	->delete( $filename );
```

## Configuring

### File Driver

```php

return array(
	'locations' => array(
		'sample-location' => array(
			'driver' => 'file', // required; options are: 'file'
			'base' => '/path/to/upload/root', // default: public; can be 'app', 'base', 'public' or 'storage' to use laravel's built in paths
			'path' => 'uploads/profiles', // the path from the base to the upload directory
		),
	)
);
```

## Extending

At present, there is only the file driver built available by default. You can add other drivers with the `extend` method:

```php
Cdn::extend('s3', function($app, $options) {
	return new S3CdnDriver($options);
});
```

The callback gets called whenever a new location of that type (in this case `s3` is created) - it gets passed the current application instance, and the relavent config segment (ie. an array with `driver`, `path`, etc.)
