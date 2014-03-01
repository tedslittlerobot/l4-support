Repository
==========
# Methods

## validate()

Call this to run the validation rules defined in the `$rules` property against the input in the `$input` property. It will return true or false, depending on the result, and stores the Validator object in the repository's `$val` property.

It also takes the data and files arrays that the Validator provides, and stores them in `$data` and `$files` properties.

## files and data( $key = null, $default = null )

These are convenience methods to access the `$data` and `$files` properties mentioned above. If no arguments are passed, their will return the whole or their respective arrays.

If a key is passed, they will get that property from the array, returning $default if nothing is found (it uses Illuminate's `array_get` function)

## assign( $keys, $property = 'model' )

A convenient way to directly assign keys to a model. You pass in a key, or array of keys. It will loop through the data array, assigning the value for each key to the model.

It uses the `$model` property by default, but if you are making a class that handles more than one model at once, you can pass the property key as a second argument. For example:

```php
// if you had both a model, and related property
protected $model;
protected $related;

...
// you can assign to both easily
$this->assign(['firstname', 'lastname']);
$this->assign('groupname', 'related');
```

## save()

By default, calls `$this->model->save()`. Feel free to override that behaviour.

## getErrors()

This gets the errors MessageBag from the validator. Be aware that this will throw an exception if validation has yet to be run.

