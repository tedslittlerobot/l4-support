<?php namespace Tlr\Support;

// use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class OrderEnforcer {

	public function __construct( Orderer $orderer )
	{
		$this->orderer = $orderer;
	}
	/**
	 * Enforce an order
	 * In a list of ORM models
	 * Starting from a base
	 *
	 * @param  array $list
	 */
	public function enforce( $list, $base = 1, $key = 'index' )
	{
		foreach ($list as $model)
		{
			$model->$key = $base++;

			if ($model->isDirty( $key ))
			{
				$model->save();
			}
		}

		return $list;
	}

	/**
	 * Insert an item
	 * Into a list of items
	 * Adjusts the order
	 *
	 * @param  array $list
	 */
	public function insert( $list, $item, $base = 1, $key = 'index' )
	{
		$this->orderer->setBase( $base );
		return $this->enforce( $this->orderer->insert( $list, $item, $item->{$key} ), $base, $key );
	}

	/**
	 * In a list of things
	 * Moves one thing to somewhere else
	 * And sets the order
	 */
	public function move( $list, $fromIndex, $toIndex, $base = 1, $key = 'index' )
	{
		$this->orderer->setBase( $base );
		return $this->enforce( $this->orderer->move( $list, $fromIndex, $toIndex ), $base, $index );
	}

	/**
	 * It's some shorthand code
	 * To take a relationship
	 * And enforce order
	 *
	 * @param  Model   $parent       the parent model
	 * @param  string  $relationship the relationship name
	 * @param  string  $key          the order key - defaults to order
	 * @param  closure $subquery     an optional callback that will be passed the query object to constrict it
	 * @return Collection
	 */
	public function enforceRelationship( Model $parent, $relationship, $key = 'index', $base = 1, $subquery = null )
	{
		$query = $parent->{$relationship}()->orderBy($key);

		if ( is_callable($subquery) )
		{
			call_user_func($subquery, $query);
		}

		$items = $query->get();

		return $this->enforce( $items, $base, $key );
	}
}
