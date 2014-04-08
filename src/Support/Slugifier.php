<?php namespace Tlr\Support;

use Illuminate\Support\Str;

class Slugifier {

	/**
	 * Make a unique slug
	 * Care must be taken - you risk
	 * an infinite loop
	 *
	 * Comparator:
	 * The second argument is a comparator function (or callable). It receives a string as an
	 * argument, and should return true if the string is unique.
	 *
	 * Incrementor:
	 * The third argument is a callable that takes two arguments - the base slug, and the
	 * number of iterations this has failed. The default incrementor appends a hyphen,
	 * followed by the index of the interation.
	 *
	 * @param  string  $string
	 * @param  Closure $comparator
	 * @param  Closure  $incrementor
	 * @return string
	 */
	public function incrementalSlug($string, $comparator, $incrementor = null)
	{
		$string = Str::slug($string);

		$incrementor = $this->getIncrementor($incrementor);

		if ( call_user_func($comparator, $string) )
		{
			return $string;
		}

		$xi = 1;
		while ( true )
		{
			$newStr = call_user_func( $incrementor, $string, $xi++ );

			if (call_user_func($comparator, $newStr))
			{
				$string = $newStr;
				break;
			}
		}

		return $string;
	}

	/**
	 * A helper function
	 * To get a default value
	 * If null is given
	 *
	 * @param  Closure $incrementor
	 * @return Closure
	 */
	public function getIncrementor($incrementor)
	{
		if ( ! is_null($incrementor) )
		{
			return $incrementor;
		}

		return array($this, 'numericIncrementor');
	}

	/**
	 * A basic function
	 * That appends index numbers
	 * On to given strings
	 *
	 * @param  string  $string
	 * @param  integer $iteration
	 * @return string
	 */
	public function numericIncrementor($string, $iteration)
	{
		return "{$string}-{$iteration}";
	}

	/**
	 * @TOOD - look into regex matcher
	 */

}
