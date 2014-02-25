<?php

use Illuminate\Support\Str;

class Slugifier {

	/**
	 * Custom Slugification
	 * @param  string  $string
	 * @param  Closure $comparator
	 * @param  Closure  $incrementor
	 * @return string
	 */
	public function incrementalSlug($string, $comparator, $incrementor = null)
	{
		$incrementor = $this->getIncrementor($incrementor);

		if ( call_user_func($comparator, $string) )
		{
			return $string;
		}

		$xi = 1;
		while ( true ) {
			$newStr = call_user_func( $incrementor, $string, $xi++ );

			if (call_user_func($comparator, $newStr))
			{
				return $newStr;
			}
		}
	}

	/**
	 * Get an incrementor
	 * @param  Closure $incrementor
	 * @return Closure
	 */
	public function getIncrementor($incrementor)
	{
		if (is_callable($incrementor))
		{
			return $incrementor;
		}

		return function($string, $iteration)
		{
			return "{$string}-{$iteration}";
		};
	}

}