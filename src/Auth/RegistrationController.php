<?php namespace Tlr\Auth;

// use Auth;
use Controller;
use Input;
use Redirect;
use URL;
use View;
use I18n\Territory;

class RegistrationController extends Controller {

	public function __construct( RegistrationRepository $repo )
	{
		$this->repo = $repo;
	}

	/**
	 * Show a user registration form
	 * @return View
	 */
	public function registerForm()
	{
		return View::make('auth.register');
	}

	/**
	 * Register the user
	 * @return RedirectResponse
	 */
	public function register()
	{
		if ( $user = $this->repo->register() )
		{
			// Auth::login( $user );
			return Redirect::route('pending');
		}

		return Redirect::back()
			->withInput()
			->withErrors($this->repo->getErrors());
	}

	// For a logged in user registering a new user
	public function registerNewUserForm()
	{
		return View::make('auth.register-new-user');
	}


	// For a logged in user registering a new user
	public function registerNewUser()
	{
		$registration = $this->repo->registerNewUser();

		if ($registration) {
			return View::make('private.portal')->with('messages', $this->repo->messages);
		} else {
			return Redirect::back()
				->withInput()
				->withErrors($this->repo->messages);
		}
	}

	// For users awaiting approval after applying
	public function pending()
	{
		return View::make('auth.pending');
	}

	public function handleRegisterToken($token)
	{
		return 'LOGIN!!!';
	}

}
