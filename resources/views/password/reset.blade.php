@extends('l4-auth::layout')

@section('content')

	{{ Form::open( [ 'route' => 'password.request.process' ] ) }}

		<input type="hidden" name="token" value="{{ $token }}">

		<fieldset>

			<legend>Reset Your Password</legend>

			@foreach ( $errors->all() as $error )
					<div class="danger alert">{{ $error }}</div>
			@endforeach

			<div class="field">
				<input type="email" class="input" name="email" placeholder="Email Address">
			</div>

			<div class="field">
				<input type="password" class="input" name="password" placeholder="New Password">
			</div>

			<div class="field">
				<input type="password" class="input" name="password_confirmation" placeholder="Repeat That Password!">
			</div>

			<div class="medium primary btn">
				{{ Form::submit() }}
			</div>

		</fieldset>

	{{ Form::close() }}

@stop
