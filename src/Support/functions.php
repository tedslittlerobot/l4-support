<?php

if ( ! function_exists('dot_get'))
{
	/**
	 * Get a property out of mixed objects and arrays by dot notation
	 *
	 * @param  mixed    $haystack
	 * @param  string   $key
	 * @param  mixed    $default
	 * @return string
	 */
	function dot_get($haystack, $key, $default = null)
	{
		if (is_null($key)) return $haystack;

		foreach (explode('.', $key) as $segment)
		{
			if ( ! (is_object($haystack) || is_array($haystack) ) )
			{
				return value($default);
			}

			if (is_object($haystack) && !isset($haystack->{$segment}))
			{
				return value($default);
			}

			if (is_array($haystack) && !isset($haystack[$segment]))
			{
				return value($default);
			}

			$haystack = is_object($haystack) ? $haystack->{$segment} : $haystack[$segment];
		}

		return $haystack ?: value($default);
	}
}

if ( ! function_exists('array_find_dot'))
{
	/**
	 * Find an item of an array by a dot notation accessible property
	 *
	 * @param  string  $needle
	 * @param  array   $haystack
	 * @param  string  $key
	 * @param  mixed   $default
	 * @return string
	 */
	function array_find_dot( $needle, array $haystack, $location, $default = null, $returnItem = true )
	{
		foreach ($haystack as $key => $value)
		{
			if ( dot_get( $value, $location ) == $needle )
			{
				return $returnItem ? $value : $key;
			}
		}

		return $default;
	}
}

if ( ! function_exists('array_splice_item'))
{
	/**
	 * Find an item of an array by a dot notation accessible property
	 *
	 * @param  string  $needle
	 * @param  array   $haystack
	 * @param  string  $key
	 * @param  mixed   $default
	 * @return string
	 */
	function array_splice_item( array &$haystack, $item, $default = null )
	{
		if ( ($key = array_search($item, $haystack)) === false)
		{
			return null;
		}

		return array_splice($haystack, $key, 1)[0];
	}
}

if ( ! function_exists('path_compile'))
{
	/**
	 * Put a bunch of path components together
	 *
	 * @param  string|array  $paths
	 * @return string
	 */
	function path_compile( $paths = '' )
	{
		$paths = is_array($paths) ? $paths : func_get_args();

		foreach ($paths as $xi => &$component)
		{
			$component = ($xi == 0) ? rtrim($component, '/') : trim($component, '/');
		}

		return implode('/', $paths);
	}
}

if ( ! function_exists('class_dirname'))
{
	/**
	 * Get the class "dirname" of the given object / class.
	 *
	 * @param  string|object  $class     The object or class to dirname
	 * @param  integer        $levels    How many levels to dirname (default: 1)
	 * @param  boolean        $basename  Whether or not to basename the dirname (default: false)
	 * @return string
	 */
	function class_dirname($class, $levels = 1, $basename = false)
	{
		$class = is_object($class) ? get_class($class) : $class;

		$classpath = str_replace('\\', '/', $class);

		for ( $xi = 0; $xi < $levels; $xi++ )
		{
			$classpath = dirname($classpath);
		}

		$classpath = str_replace('/', '\\', $classpath);

		return $basename ? class_basename( $classpath ) : $classpath;
	}
}
