Slugifier
=========

> A class to aid in the quest to make unique slugs

## incrementalSlug($string, $comparator, $incrementor = null)


### Comparator

The comparator is a function that takes a string as its only argument.

If the comparator returns false, then that string is rejected, and the function will continue to find a suitable slug.
If it returns true, then that string is accepted, and will be returned from the `incrementalSlug` function.

```php
$comparator = function($string)
{
	return Post::where('slug', $string)->count() == 0;
};
$slugifier->incrementalSlug('Hello World', $comparator);
```

### Incrementor

The default incrementor adds a `-n` to the string, where `n` is the incremental index. You can pass another one in - it accepts two arguments:

```php
function ($string, $iteration)
{
	return "{$string}-{$iteration}";
}
```
