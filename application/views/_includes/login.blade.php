@if( !Auth::check() )
	<form action="" class="login clearfix" method="post">
		<div class="field required clearfix">
			{{ Form::label('__username', 'Email') }}
			{{ Form::input('text', '__username', Input::old('__username')) }}
		</div>
		<div class="field required clearfix">
			{{ Form::label('__password', 'Password') }}
			{{ Form::password('__password') }}
		</div>
		@if( Session::get('login-message') )
			<div class="field">
				<p class="error clearfix">{{ Session::get('login-message') }}</p>
			</div>
		@endif
		<div class="submit">
			{{ Form::submit('Login', array('class'=>'btn')) }}
		</div>
		<a class="btn btn-sub" href="/user/join">Join Billbot</a> <a class="btn btn-sub" href="/user/reset">Reset password</a>
	</form>
@else
	<p class="logged-in">
		<a class="icon-with-text user" title="Logged in" href="/user">
			@if( Auth::user()->forename ) 
				{{ Auth::user()->forename }} {{ Auth::user()->surname }}
			@else
				{{ Auth::user()->username }}
			@endif
		</a>
		<a class="icon-with-text logout" href="?logout=true">Logout</a>
</p>
@endif