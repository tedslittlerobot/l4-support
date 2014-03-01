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

# UserRepository

```php
class UserRepository extends Repository {

	protected $rules = [
		'firstname' => 'required',
		'lastname' => 'required',
		'email' => 'required|email',
	];

	/**
	 * Laravel's IOC will inject a blank User model
	 */
	public function __construct( User $user )
	{
		$this->model = $user;
		$this->setInput( Input::get() );
	}

	/**
	 * The create function will save a new User to the database
	 */
	public function create()
	{
		// We add a validation rule specific to the first time
		// creation of a User
		$this->addRule('password', 'required|confirmed');

		// Then, we run the validation...
		if ( ! $this->validate() )
		{
			// ...returning false if it fails
			return false;
		}

		/**
		 * The validate method runs validation (obviously), and puts the
		 * data into a $files and $data array. This is accessible through
		 * the methods $this->data() and $this->file().
		 */

		// Then we set the data on the model
		$this->fill();
		// Then do any create-specific manipulation
		$this->model->password = Hash::make( $this->data('password') );
		// And save!
		$this->save();

		// Finally return the model
		return $this->model;
	}

	/**
	 * The update method, that takes the model of the user to update
	 */
	public function update( Eloquent $user )
	{
		$this->model = $user;

		if ( ! $this->validate() ) return false;

		$this->fill()->save();

		return $this->model;
	}

	/**
	 * Override the fill method
	 */
	protected function fill()
	{
		// First up, call the parent's fill method. This is
		// basically $this->model->fill( $this->data() )
		parent::fill();

		// Add any other common data assignments
		// I'll admit, this is a contrived example, but I'm just
		// showing what *can* be done ;)
		$this->model->firstname = trim( ucwords($this->data('firstname')) );
		$this->model->lastname = trim( ucwords($this->data('lastname')) );
	}

}
```

# UserController

```php

class UserController extends Controller {

	public function __construct( UserRepository $repo )
	{
		$this->repo = $repo;
	}

	public function store()
	{
		if( ! $user = $this->repo->create() )
		{
			return Redirect::back()
				->withInput()
				->withErrors( $this->repo->getErrors() );
		}

		return Redirect::route('user', ['user' => $user->id]);
	}

	public function update( User $user )
	{
		if( ! $user = $this->repo->update( $user ) )
		{
			return Redirect::back()
				->withInput()
				->withErrors( $this->repo->getErrors() );
		}

		return Redirect::route('user', ['user' => $user->id]);
	}
}

```
