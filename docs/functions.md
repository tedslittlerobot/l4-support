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

## array_find_dot

```php
array_find_dot($needle, $haystack, $key, $default = null, $returnItem = true);
```

Find the first item in the given array where the `$key => $needle` (when evaluated using `dot_get`, as described above) is true.

> @TODO add an example


## array_splice_item

```php
array_splice_item(&$haystack, $item, $default = null);
```

Splice the given item from the given array. Essentially a macro for array_splice on the result of array_search.

> @TODO add an example
