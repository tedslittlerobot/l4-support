Support Functions
=================

## dot_get

```php
dot_get($haystack, $needle, $default = null);
```

This is an extension of Illuminate's `array_get()` that allows for mixed objects and arrays.

If you had the following:

```php
$array = array(
	'one' => (object)[
			'two' => 'three',
		],
);
```

ie. an array, with a key `one`, pointing to an object, with a key, `two`, with the value `three`, you could do the following:

```php
dot_get($array, 'one.two'); // outputs   string(5) "three"
```


