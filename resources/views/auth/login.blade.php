@extends('l4-auth::layout')

@section('content')

	{{ Form::open( [ 'route' => 'login.attempt' ] ) }}

		<fieldset>

			<legend>Log In</legend>

			@if ( $errors->first() )
					<div class="danger alert">{{ $errors->first() }}</div>
			@endif

			<div class="field">
				<input type="text" class="input" name="email" placeholder="Email Address">
			</div>

			<div class="field">
				<input type="password" class="input" name="password" placeholder="Password">
			</div>


			<div class="medium primary btn">
				{{ Form::submit() }}
			</div>

		</fieldset>

	{{ Form::close() }}

	<a href="{{ route('password.request') }}">
		Forgotten Your Password?
	</a>

@stop
