@layout('_templates.form-page')

@section('pagetitle') Create password @endsection

@section('primary')
	
	@if( Session::has('error') )
		<p class="error">{{ Session::get('error') }}</p>
	@endif
	
	<p>Enter your new password below</p>

	{{ Form::open( URI::current() ) }}

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

		<div class="field submit">
			<input type="hidden" name="hash" value="{{ Input::get('hash') }}">
			<input type="submit" class="btn" value="Update password">
		</div>
	{{ Form::close() }}

@endsection