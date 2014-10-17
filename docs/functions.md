Support Functions
=================

## dot_get

> dot_get($haystack, $needle, $default = null);

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

> array_find_dot($needle, $haystack, $key, $default = null, $returnItem = true);

Find the first item in the given array where the `$key => $needle` (when evaluated using `dot_get`, as described above) is true.

If you have an array of models, for example, you can find one specifically by, say, slug:

```php
array_find_dot( 'page-four', $pages, 'slug' ); // will return the page with a slug of 'page-four'
```

As mentioned above, this uses `dot_get` to match the items, so this can be used on multi-dimentional object and array combinations.


## array_splice_item

> array_splice_item(&$haystack, $item, $default = null);

Splice the given item from the given array. Essentially a macro for `array_splice` on the result of `array_search`.

For example, using the array `['one', 'two', 'three']`:

```php
$item = array_splice_item($array, 'two');
echo $item; // echoes 'two'
echo json_encode($array); // echoes ["one", "three"]
```

## class_dirname

> class_dirname($class, $levels = 1, $basename = false);

A complimentary function to laravel's own `class_basename`, this takes an object, or fully namespaced class string, and runs php's dirname function on it.

An optional second argument allows you to go up multiple levels

The third argument allows you to automatically call `class_basename` on the result

```php
use App\Content\Type\Widget\Controller;

class_dirname( Controller::class ); // App\Content\Type\Widget
class_dirname( Controller::class, 3 ); // App\Content
class_dirname( Controller::class, 1, true ); // Widget
```
