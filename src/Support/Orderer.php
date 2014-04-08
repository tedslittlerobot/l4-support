<?php namespace Tlr\Support;

class Orderer {

	protected $base = 0;

	/**
	 * Set the index base
	 * @param  integer   $base
	 */
	public function setBase( $base )
	{
		$this->base = $base;
		return $this;
	}

	/**
	 * Get the index base
	 * @return integer
	 */
	public function getBase()
	{
		return $this->base;
	}

	/**
	 * Convert an array index into an order index
	 * @param  integer   $order
	 * @return integer
	 */
	public function index( $order )
	{
		return $order - $this->getBase();
	}

	/**
	 * Convert an order index into an array index
	 * @param  integer   $index
	 * @return integer
	 */
	public function order( $index )
	{
		return $index + $this->getBase();
	}

	/**
	 * Items in a list
	 * Each one shall have their index
	 * Assigned to a key
	 *
	 * @param  array   $list
	 * @param  string   $key    the property to assign the order to
	 * @return array
	 */
	public function assignIndices( $list, $key = 'index' )
	{
		$newList = array_values( $list );

		foreach ( $newList as $index => $value ) {

			if (is_object($value))
				$newList[$index]->$key = $this->order( $index );

			if (is_array($value))
				$newList[$index][$key] = $this->order( $index );

		}

		return $newList;
	}

	/**
	 * A wrapper around
	 * array_splice that converts 'twixt
	 * An index and base
	 *
	 * @param  mixed   $new
	 * @param  array   $list
	 * @param  integer   $index  the order index
	 * @return array
	 */
	public function insert( array $list, $new, $index )
	{
		array_splice( $list, $this->index($index), 0, [$new] );

		return $list;
	}

	/**
	 * To move an item
	 * In the context of a list
	 * To another place
	 *
	 * @param  integer   $index       the order index to move from
	 * @param  array   $list
	 * @param  integer   $targetIndex the order index to move to
	 * @return array
	 */
	public function move( $list, $index, $targetIndex )
	{
		list($item) = array_splice( $list, $this->index($index), 1 );

		return $this->insert( $list, $item, $targetIndex );
	}

	/**
	 * Like the move method,
	 * But performs an array_search
	 * To get the index
	 *
	 * @param  mixed   $item
	 * @param  array   $list
	 * @param  integer   $targetIndex
	 * @return array
	 */
	public function moveItem( $list, $item, $targetIndex )
	{
		$index = array_search( $item, $list );
		return $this->move( $list, $this->order($index), $targetIndex );
	}

}
