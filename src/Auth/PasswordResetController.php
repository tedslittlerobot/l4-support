<?php namespace Tlr\Auth;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Tlr\Auth\User;

class PasswordResetController extends Controller {

	/**
	 * The Password reset request view
	 * @var string
	 */
	public static $requestView = 'l4-auth::password.request';

	/**
	 * The Password reset view
	 * @var string
	 */
	public static $resetView = 'l4-auth::password.reset';

	/**
	 * Display the password reminder view.
	 *
	 * @return Response
	 */
	public function request()
	{
		return View::make( self::$requestView );
	}

	/**
	 * Handle a POST request to remind a user of their password.
	 *
	 * @return Response
	 */
	public function processRequest()
	{
		switch ( $response = Password::remind( Input::only('email') ) )
		{
			case Password::INVALID_USER:
				return Redirect::back()->with('error', Lang::get( $response ));

			case Password::REMINDER_SENT:
				return Redirect::back()->with('status', Lang::get( $response ));
		}
	}

	/**
	 * Display the password reset view for the given token.
	 *
	 * @param  string  $token
	 * @return Response
	 */
	public function reset($token = null)
	{
		if ( is_null( $token ) )
		{
			App::abort(404);
		}

		return View::make( self::$requestView )->with( 'token', $token );
	}

	/**
	 * Handle a POST request to reset a user's password.
	 *
	 * @return Response
	 */
	public function processReset()
	{
		$credentials = Input::only(
			'email', 'password', 'password_confirmation', 'token'
		);

		$response = Password::reset($credentials, function( User $user, $password )
		{
			$user->password = $password;

			$user->save();
		});

		switch ( $response )
		{
			case Password::INVALID_PASSWORD:
			case Password::INVALID_TOKEN:
			case Password::INVALID_USER:
				return Redirect::back()->with( 'error', Lang::get( $response ) );

			case Password::PASSWORD_RESET:
				return Redirect::route('login');
		}
	}

}
