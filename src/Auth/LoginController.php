<?php namespace Tlr\Auth;

use Illuminate\Routing\Controller;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;

class LoginController extends Controller {

	public static $loginView = 'l4-auth::login';

	/**
	 * Redirect the user to the login page
	 * @return RedirectResponse
	 */
	public function redirectToLogin()
	{
		return Redirect::route('login');
	}

	/**
	 * Show a login form
	 * @return View
	 */
	public function loginForm()
	{
		return View::make( self::$loginView );
	}

	/**
	 * Attempt to log the user in
	 * @return RedirectResponse
	 */
	public function login()
	{
		if ( Auth::attempt([ 'email' => Input::get('email'), 'password' => Input::get('password') ]) )
		{
			return Redirect::intended( $this->getAdminUrl() );
		}
		else
		{
			return Redirect::route('login')->withErrors( [ 'denied' ] );
		}
	}

	/**
	 * Log the user out
	 * @return RedirectResponse
	 */
	public function logout()
	{
		Auth::logout();

		return Redirect::to( $this->getLoggedOutUrl() );
	}

	/**
	 * Get the admin URL for the application
	 * @return string
	 */
	protected function getAdminUrl()
	{
		if( Route::getRoutes()->hasNamedRoute('admin') )
		{
			return URL::route('admin');
		}
		else
		{
			return URL::to('/');
		}
	}

	/**
	 * Get the admin URL for the application
	 * @return string
	 */
	protected function getLoggedOutUrl()
	{
		if( Route::getRoutes()->hasNamedRoute('home') )
		{
			return URL::route('home');
		}
		else
		{
			return URL::to('/');
		}
	}

}
