@layout('_templates.login')

@section('primary')
	
	<h1>Sign up</h1>

	{{ Form::open() }}

		<div class="field required">
			{{ Form::label('username', 'Username') }}
			{{ Form::text('username', Input::old('username')) }}
			{{ $errors->has('username') ? $errors->first('username', '<p class="error">:message</p>') : '' }}
		</div>
		<div class="field required">
			{{ Form::label('email', 'Email') }}
			{{ Form::text('email', Input::old('email')) }}
			{{ $errors->has('email') ? $errors->first('email', '<p class="error">:message</p>') : '' }}
		</div>
		<div class="field required">
			{{ Form::label('password', 'Password') }}
			{{ Form::password('password') }}
			{{ $errors->has('password') ? $errors->first('password', '<p class="error">:message</p>') : '' }}
		</div>
		<div class="field required">
			{{ Form::label('password_confirm', 'Confirm password') }}
			{{ Form::password('password_confirm') }}
			{{ $errors->has('password_confirm') ? $errors->first('password_confirm', '<p class="error">:message</p>') : '' }}
		</div>

		<a class="btn" href="/">Cancel</a>
		<div class="field submit">	
			<input type="submit" class="btn" value="Add">
		</div>
	{{ Form::close() }}

@endsection