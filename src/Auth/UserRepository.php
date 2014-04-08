<?php namespace Tlr\Auth;

use Input;
use Tlr\Auth\User;
use Tlr\Support\Repository;

class UserRepository extends Repository {

	/**
	 * Dem rulz
	 * @var array
	 */
	protected $rules = [
		'firstname' => 'required',
		'lastname' => 'required',
		'email' => 'email|unique:users',
		'password' => 'confirmed',
	];

	public function __construct( User $user )
	{
		$this->input = Input::get();
		$this->model = $user;
	}

	/**
	 * @inheritdoc
	 */
	public function fill()
	{
		$this->model->fill( $this->data() );

		$this->assign( [ 'email', 'password', 'permissions' ] );
	}

	/**
	 * Create a new user
	 * @return Tlr\Auth\User
	 */
	public function create()
	{
		if( !$this->validate() )
		{
			return false;
		}

		$this->fill();
		$this->save();

		return $this->model;
	}

	/**
	 * Update an existing user
	 * @return Tlr\Auth\User
	 */
	public function update( $user )
	{
		$this->model = $user;

		if( !$this->validate() )
		{
			return false;
		}

		$this->fill();
		$this->save();

		return $this->model;
	}

}
