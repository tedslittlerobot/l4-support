<?php

if ( ! function_exists('can'))
{
	/**
	 * Check if a user is logged in, and has the given permission(s)
	 *
	 * @param  string|array  $permissions
	 * @return boolean
	 */
	function can( $permissions = array() )
	{
		if ( $user = Auth::user() )
		{
			return $user->can( $permissions );
		}

		return false;
	}
}
