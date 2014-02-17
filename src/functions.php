<?php

if ( ! function_exists('dot_get'))
{
	/**
	 * Get a property out of mixed objects and arrays by dot notation
	 *
	 * @param  string  $name
	 * @param  array   $parameters
	 * @return string
	 */
	function dot_get($item, $key, $default = null)
	{
		if (is_null($key)) return $item;

		foreach ($explode('.', $key) as $segment)
		{
			if ( ! (is_object($item) || is_array($item) ) )
			{
				return value($default);
			}

			$item = is_object($item) ? $item->{$segment} : $item[$segment];
		}

		return $item ?: $default;
	}
}

if ( ! function_exists('array_find'))
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
	function array_find( $needle, array $haystack, $key, $default = null )
	{
		foreach ($haystack as $key => $value)
		{
			if ( dot_get( $value ) === $key )
			{
				return $value;
			}
		}

		return $default;
	}
}
