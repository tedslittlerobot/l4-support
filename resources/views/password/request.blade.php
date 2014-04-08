@extends('l4-auth::layout')

@section('content')

	{{ Form::open( [ 'route' => 'password.request.process' ] ) }}

		<fieldset>

			<legend>Request Password Reset</legend>

			@foreach ( $errors->all() as $error )
					<div class="danger alert">{{ $error }}</div>
			@endforeach

			<div class="field">
				<input type="email" class="input" name="email" placeholder="Email Address">
			</div>

			<div class="medium primary btn">
				{{ Form::submit() }}
			</div>

		</fieldset>

	{{ Form::close() }}

@stop
