@layout('_templates.login')

@section('pagetitle') Forgotten password @endsection

@section('primary')
	
	@if( Session::has('success') )
		<p class="success">{{ Session::get('success') }}</p>
	@endif

	{{ Form::open() }}

		<div class="field required">
			{{ Form::label('email', 'Email') }}
			{{ Form::text('email', Input::old('email')) }}
			{{ $errors->has('email') ? $errors->first('email', '<p class="error">:message</p>') : '' }}
		</div>

		<a class="btn" href="/">Home</a>
		<input type="submit" class="btn submit" value="Send">

	{{ Form::close() }}

@endsection