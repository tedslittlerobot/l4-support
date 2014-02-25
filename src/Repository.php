<?php namespace Tlr\Support;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\Validator;

use Tlr\Support\Database\Orderer;

class Repository {

	public function __construct( Orderer $orderer = null)
	{
		$this->orderer = $orderer;
	}

	/**
	 * An Eloquent model
	 * @var Illuminate\Database\Eloquent\Model
	 */
	protected $model;

	/**
	 * Input to validate
	 * @var array
	 */
	protected $input = array();

	/**
	 * Validate rules
	 * @var array
	 */
	protected $rules = array();

	/**
	 * The validated data
	 * @var array
	 */
	protected $data = array();

	/**
	 * File objects
	 * @var array
	 */
	protected $files = array();

	/**
	 * Set the data to validate
	 * @param  array $input
	 */
	public function setInput( $input )
	{
		$this->input = $input;

		return $this;
	}

	/**
	 * Get the data to validate
	 * @return array
	 */
	public function getInput()
	{
		return $this->input;
	}

	/**
	 * Set the primary model that this repository is handling
	 * @param  Eloquent $model
	 */
	public function setModel( Eloquent $model )
	{
		$this->model = $model;

		return $this;
	}

	/**
	 * Get the primary model that the repo is validating
	 * @return Eloquent $model
	 */
	public function getModel()
	{
		return $this->model;
	}

	/**
	 * Get the rules to validate against
	 * @return array
	 */
	public function getRules()
	{
		return $this->rules;
	}

	/**
	 * Set the whole rules array
	 * @param  array $rules
	 */
	public function setRules( $rules )
	{
		$this->rules = $rules;

		return $this;
	}

	/**
	 * Set a specific rule
	 * @param  string $key
	 * @param  mixed  $rules
	 */
	public function addRule( $key, $rules )
	{
		$this->rules[ $key ] = $rules;

		return $this;
	}

	/**
	 * Get the filtered input data, or a specific peice of that data
	 * @param string $key
	 * @param mixed  $default
	 * @return mixed
	 */
	public function data( $key = null, $default = null )
	{
		if ( is_null($key) )	return $this->data;

		return array_get( $this->data, $key, $default );
	}

	/**
	 * Get the files array, or a specific file
	 * @return mixed
	 */
	public function file( $key = null, $default = null )
	{
		if ( is_null($key) )	return $this->files;

		return array_get( $this->files, $key, $default );
	}

	/**
	 * Perform some validation
	 * @return boolean
	 */
	public function validate()
	{
		$this->val = Validator::make( $this->getInput(), $this->getRules() );

		$this->data = $this->val->getData();

		$this->files = $this->val->getFiles();

		return $this->val->passes();
	}

	/**
	 * Do something with all that freshly validated data
	 */
	protected function fill()
	{
		$this->model->fill( $this->data() );

		return $this;
	}

	/**
	 * Assign the $keys the $model, if they are present
	 * @param  array|string $keys
	 * @param  string $property
	 */
	protected function assignIfExists( $keys, $property = 'model' )
	{
		foreach ( (array)$keys as $key)
		{
			if ( ! is_null( $this->data($key) ) )
			{
				$this->$property->$key = $this->data($key);
			}
		}
	}

	/**
	 * Save the models to the database
	 */
	protected function save()
	{
		$this->model->save();

		return $this;
	}

	/**
	 * Get the validation errors
	 * @return array|MessageBag
	 */
	public function getErrors()
	{
		return $this->val->getMessageBag();
	}

	/**
	 * Insert an item into a collection
	 * @param  array $list
	 */
	protected function insert( array $list, $base = 1 )
	{
		foreach( $this->orderer->insert( $this->model, $list, $this->model->index ) as $model )
		{
			$model->index = $base++;

			if ($model->isDirty('index'))
			{
				$model->save();
			}
		}

		return $this;
	}

}
