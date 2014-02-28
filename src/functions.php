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
