Order Enforcer
==============

> A class to aid in the reordering of Eloquent models

## enforce( $list, $base = 1, $key = 'index' )

The core of the class. This will loop through the `$list` in order, and sets the `$key` property (defaults to `index`) on each one to its index in the array. The first one will have an index of `$base`, the second, `$base + 1`, the rest `$base + $n`, etc.

If the index of the model has changed (it uses Eloquent's `isDirty($key)` method to determine this), it will call `save()` on it.

## insert( $list, $item, $base = 1, $key )

Will call insert on an instance of `Tlr\Support\Database\Orderer`, and passes the result to `enforce`.

## move( $list, $item, $base = 1, $key )

Will call move on an instance of `Tlr\Support\Database\Orderer`, and passes the result to `enforce`.

## enforceRelationship( Eloquent $model, $relationship, $key = 'index', $base = 1, $subquery = null )

Queries the `$relationship` of the `$model`, and passes the result to `enforce`.

Say you have a User model, with a hasMany relationship, `jobs`, to a Job model. The jobs must be displayed in order.

```php
$orderEnforcer->enforceRelationship( $user, 'jobs' );
```

This will make sure that the indices on the Job objects start at 1, and do not skip any numbers

The fifth argument - optional, can be a closure that will be called to narrow down the query.

```php
$orderEnforcer->enforceRelationship( $user, 'jobs', 'index', 1, function($query)
{
	$query->whereTrue('visible');
} );
```

