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
