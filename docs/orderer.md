Orderer
=======

> A class to aid the reording of items in an array

## insert( array $list, $item, $index )

Insert `$item` into `$list` at `$index`

```php
$array = ['one', 'two', 'three'];

$orderer->insert($array, 'zero', 1); // returns ['one', 'zero', 'two', 'three']
```

## move( $list, $fromIndex, $targetIndex )

Move an item from an index to another index

```php
$array = ['one', 'two', 'three'];

$orderer->move($array, 1, 0); // returns ['two', 'one', 'three']
```

## moveItem( $list, $item, $targetIndex )

Move an item to a target index. Essentially `array_search` with `$orderer->move()`

```php
$array = ['one', 'two', 'three'];

$orderer->moveItem($array, 'two', 0); // returns ['two', 'one', 'three']
```

## assignIndices( $list, $key = 'index' )

Takes an array of either objects or arrays, and adds a property to each of them - by default, it will add it to the key 'index', but that can be changed with the second argument.

```php
$array = [ [], [], [] ];

$orderer->assignIndices( $array ); // returns [ ['index' => 0], ['index' => 1], ['index' => 2] ]
```

## setBase( $base )

The Orderer class can handle 0 or 1 based indices for you. It is 0-based by default.

```php
$array = ['one', 'two', 'three'];

// zero-based index
$orderer->insert($array, 'zero', 1); // returns ['one', 'zero', 'two', 'three']

// one-based index
$orderer->setBase(1);
$orderer->insert($array, 'zero', 1); // returns ['zero', 'one', 'two', 'three']
```

This also affects the index applied by `assignIndices`
