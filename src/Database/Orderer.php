<?php namespace Tlr\Support\Database;

class Orderer {

	protected $base = 1;

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
	 * Assign the converted array index to a keyed property of the elements
	 * @param  array   $list
	 * @param  string   $key    the property to assign the order to
	 * @return array
	 */
	public function assignIndices( $list, $key = 'order' )
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
	 * Insert an item into the array
	 * @param  mixed   $new
	 * @param  array   $list
	 * @param  integer   $index  the order index
	 * @return array
	 */
	public function insert( $new, array $list, $index )
	{
		array_splice( $list, $this->index($index), 0, [$new] );

		return $list;
	}

	/**
	 * Move an item in an array
	 * @param  integer   $index       the order index to move from
	 * @param  array   $list
	 * @param  integer   $targetIndex the order index to move to
	 * @return array
	 */
	public function move( $index, $list, $targetIndex )
	{
		list($item) = array_splice( $list, $this->index($index), 1 );

		return $this->insert( $item, $list, $targetIndex );
	}

	/**
	 * Find an item in an array and move it
	 * @param  mixed   $item
	 * @param  array   $list
	 * @param  integer   $targetIndex
	 * @return array
	 */
	public function moveItem( $item, $list, $targetIndex )
	{
		$index = array_search( $item, $list );
		return $this->move( $this->order($index), $list, $targetIndex );
	}

}
