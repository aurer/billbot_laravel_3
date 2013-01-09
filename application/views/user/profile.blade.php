@layout('_templates.login')

@section('pagetitle') Your Details @endsection

@section('primary')
	
	@if( Session::has('success') )
		<p class="success">{{ Session::get('success') }}</p>
	@endif

	{{ Form::open() }}

		<div class="field required">
			{{ Form::label('username', 'Username') }}
			{{ Form::text('username', $data->username) }}
			{{ $errors->has('username') ? $errors->first('username', '<p class="error">:message</p>') : '' }}
		</div>
		<div class="field">
			{{ Form::label('forename', 'Forename') }}
			{{ Form::text('forename', $data->forename) }}
			{{ $errors->has('forename') ? $errors->first('forename', '<p class="error">:message</p>') : '' }}
		</div>
		<div class="field">
			{{ Form::label('surname', 'Surname') }}
			{{ Form::text('surname', $data->surname) }}
			{{ $errors->has('surname') ? $errors->first('surname', '<p class="error">:message</p>') : '' }}
		</div>
		<div class="field required">
			{{ Form::label('email', 'Email') }}
			{{ Form::text('email', $data->email) }}
			{{ $errors->has('email') ? $errors->first('email', '<p class="error">:message</p>') : '' }}
		</div>
		<div class="field">
			{{ Form::label('password', 'Password') }}
			{{ Form::password('password') }}
			{{ $errors->has('password') ? $errors->first('password', '<p class="error">:message</p>') : '' }}
		</div>
		<div class="field">
			{{ Form::label('password_confirm', 'Confirm password') }}
			{{ Form::password('password_confirm') }}
			{{ $errors->has('password_confirm') ? $errors->first('password_confirm', '<p class="error">:message</p>') : '' }}
		</div>

		<a class="btn" href="/">Home</a>
		<input type="submit" class="btn submit" value="Save">

	{{ Form::close() }}

@endsection