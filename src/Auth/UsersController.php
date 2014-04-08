<?php namespace Tlr\Auth;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Tlr\Auth\User;
use Tlr\Auth\UserRepository;

class UsersController extends Controller {

	/**
	 * The index view
	 * @var string
	 */
	public static $indexView = 'l4-auth::user.index';

	/**
	 * The edit view
	 * @var string
	 */
	public static $editView = 'l4-auth::user.edit';

	/**
	 * The show view
	 * @var string
	 */
	public static $showUserView = 'l4-auth::user.show';

	/**
	 * The edit profile view
	 * @var string
	 */
	public static $editProfileView = 'l4-auth::user.profile';


	public function __construct( UserRepository $repository )
	{
		$this->repo = $repository;
	}

	/**
	 * Get the index of users
	 * @return View
	 */
	public function index()
	{
		$query = User::query();
		$append = array();

		$query->orderBy( Input::get('sort', 'lastname'), Input::get('order', 'DESC') );

		if ( $filter = Input::get('filter') )
		{
			$filter = strtolower( substr($filter, 0, 1) );
			$query->where('lastname', 'LIKE', $filter . '%');
		}

		foreach ( ['filter', 'sort', 'order'] as $key )
		{
			if ( $value = Input::get( $key ) )
			{
				$append[$key] = $value;
			}
		}

		$users = $query->paginate()->appends( $append );

		return View::make( self::$indexView )
			->with( 'users', $users );
	}

	/**
	 * Show a user
	 * @param  User   $user
	 * @return View
	 */
	public function show( User $user )
	{
		return View::make( self::$showUserView )
			->with('user', $user);
	}

	/**
	 * Show the current user's profile
	 * @return View
	 */
	public function profile()
	{
		return $this->show( Auth::user() );
	}

	/**
	 * Edit a user
	 * @param  User   $user
	 * @return View
	 */
	public function edit( User $user )
	{
		return View::make( self::$editView )
			->with('user', $user);
	}

	/**
	 * Edit a user
	 * @param  User   $user
	 * @return RedirectResponse
	 */
	public function update( User $user )
	{
		if ( ! $user = $this->repo->update( $user ) )
		{
			return Redirect::back()
				->withInput()
				->withErrors( $this->repo->getErrors() );
		}

		return Redirect::route('');
	}

	/**
	 * Edit the currently logged in user
	 * @return View
	 */
	public function editProfile()
	{
		return $this->edit( Auth::user() );
	}

	/**
	 * Update the currently logged in user
	 * @return RedirectResponse
	 */
	public function updateProfile()
	{
		return $this->update( Auth::user() );
	}

}
