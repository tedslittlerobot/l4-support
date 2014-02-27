<?php namespace Tlr\Support\Database;

// use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class OrderEnforcer {

	public function __construct( Orderer $orderer )
	{
		$this->orderer = $orderer;
	}
	/**
	 * Enforce a strict incremental order, starting at the given index
	 * @param  Collection $list
	 */
	public function enforce( $list, $base = 1, $key = 'index' )
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
	 * Insert an item into a list
	 * @param  array $list
	 */
	public function insert( $list, $base = 1, $key = 'index' )
	{
		return $this->enforce( $this->orderer->insert( $list, $this->model, $this->model->index ), $base, $key );
	}

	/**
	 * Move an item around an array
	 */
	public function move( $list, $fromIndex, $toIndex, $base = 1, $key = 'index' )
	{
		return $this->enforce( $this->orderer->move( $list, $fromIndex, $toIndex ), $base, $index );
	}

	/**
	 * Enforce a relationship order
	 * @param  Model   $parent       the parent model
	 * @param  string  $relationship the relationship name
	 * @param  string  $key          the order key - defaults to order
	 * @param  closure $subquery     an optional callback that will be passed the query object to constrict it
	 * @return Collection
	 */
	public function enforceRelationship( Model $parent, $relationship, $key = 'index', $base = 1, $subquery = null )
	{
		$query = $parent->{$relationship}()->orderBy('key');

		if ( is_callable($subquery) )
		{
			call_user_func($subquery, $query);
		}

		$items = $query->get();

		return $this->enforce( $items, $base, $key );
	}
}
