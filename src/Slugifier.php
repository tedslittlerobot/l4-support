<?php namespace Tlr\Support;

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
	 * Get an incrementor
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
	 * The default iterator
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
