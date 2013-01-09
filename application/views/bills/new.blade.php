@layout('_templates.default')

@section('pagetitle') Bills :: New @endsection

@section('primary')

	{{ Form::open() }}
				
		<div class="field required">
			{{ Form::label('title', 'Title') }}
			{{ Form::text('title', Input::old('title') ) }}
			{{ $errors->has('title') ? $errors->first('title', '<p class="error">:message</p>') : '' }}
		</div>
		<div class="field">
			{{ Form::label('amount', 'Amount') }}
			{{ Form::text('amount', Input::old('amount')) }}
			{{ $errors->has('amount') ? $errors->first('amount', '<p class="error">:message</p>') : '' }}
		</div>
		<div class="field">
			{{ Form::label('recurrence', 'Recurrence') }}
			{{ Form::select('recurrence', array('monthly'=>'Monthly', 'yearly'=>'Yearly', 'weekly'=>'Weekly') , Input::old('recurrence')) }}
			{{ $errors->has('recurrence') ? $errors->first('recurrence', '<p class="error">:message</p>') : '' }}
		</div>
		<div class="field required">
			{{ Form::label('renews_on', 'Renews on') }}
			{{ Form::text('renews_on', Input::old('renews_on'), array('placeholder'=>'e.g. Saturday or 25th April')) }}
			{{ $errors->has('renews_on') ? $errors->first('amount', '<p class="error">:message</p>') : '' }}
		</div>
		<div class="field checkbox">
			{{ Form::label('send_reminder', 'Send reminder?') }}
			{{ Form::checkbox('send_reminder', 'true', Input::old('send_reminder') ? true : false )}}
			{{ $errors->has('send_reminder') ? $errors->first('send_reminder', '<p class="error">:message</p>') : '' }}
		</div>
		<div class="field">
			{{ Form::label('reminder', 'Send reminder this many days before') }}
			{{ Form::number('reminder', Input::old('reminder')) }}
			{{ $errors->has('reminder') ? $errors->first('reminder', '<p class="error">:message</p>') : '' }}
		</div>
		<div class="field">
			{{ Form::label('comments', 'Comments') }}
			{{ Form::textarea('comments', Input::old('comments')) }}
			{{ $errors->has('comments') ? $errors->first('comments', '<p class="error">:message</p>') : '' }}
		</div>

		<a class="btn" href="/{{ URI::segment(1) }}">Cancel</a>
		
		<input type="submit" class="btn submit" value="Add">
	
	{{ Form::close() }}

@endsection