<?php namespace Tlr\Support\Database;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class OrderEnforcer {

	/**
	 * Enforce a strict incremental order, starting at the given index
	 * @param  Collection $list
	 */
	public function enforce( Collection $list, $base = 1 )
	{
		// we minus one because we're incrememting at the start of the loop
		$xi = $base - 1;

		foreach ($list as $item)
		{
			if ( $item->order !== ++$xi )
			{
				$item->order = $xi;
				$item->save();
			}
		}

		return $list;
	}

	/**
	 * Enforce a relationship order
	 * @param  Model   $parent       the parent model
	 * @param  string  $relationship the relationship name
	 * @param  string  $key          the order key - defaults to order
	 * @param  closure $subquery     an optional callback that will be passed the query object to constrict it
	 * @return Collection
	 */
	public function enforceRelationship( Model $parent, $relationship, $key = 'order', $base = 1, $subquery = null )
	{
		$query = $parent->{$relationship}()->orderBy('key');

		if ( is_callable($subquery) )
		{
			call_user_func($subquery, $query);
		}

		$items = $query->get();

		return $this->enforce( $items, $base );
	}
}
