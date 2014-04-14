<?php

if ( ! function_exists('dot_get'))
{
	/**
	 * Get a property
	 * using dot notation from
	 * objects or arrays.
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
	 * Search through an array
	 * Use dot notation to match
	 * Then return that thing.
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
	 * A wrapper around
	 * array_search and array_splice
	 * Convenient, no?
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
