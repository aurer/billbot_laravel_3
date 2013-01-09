@layout('_templates.default')

@section('pagetitle') Edit :: {{ $bill->title }} @endsection

@section('primary')

	{{ Form::open() }}

		<div class="field required">
			{{ Form::label('title', 'Title') }}
			{{ Form::text('title', Input::old('title') ? Input::old('title') : $bill->title  ) }}
			{{ $errors->has('title') ? $errors->first('title', '<p class="error">:message</p>') : '' }}
		</div>
		<div class="field">
			{{ Form::label('amount', 'Amount') }}
			{{ Form::text('amount', Input::old('amount') ? Input::old('amount') : $bill->amount ) }}
			{{ $errors->has('amount') ? $errors->first('amount', '<p class="error">:message</p>') : '' }}
		</div>
		<div class="field">
			{{ Form::label('recurrence', 'Recurrence') }}
			{{ Form::select('recurrence', array('monthly'=>'Monthly', 'yearly'=>'Yearly', 'weekly'=>'Weekly') , Input::old('recurrence') ? Input::old('recurrence') : $bill->recurrence ) }}
			{{ $errors->has('recurrence') ? $errors->first('recurrence', '<p class="error">:message</p>') : '' }}
		</div>
		<div class="field required">
			{{ Form::label('renews_on', 'Renews on') }}
			{{ Form::text('renews_on', $bill->renews_on, array('placeholder'=>'e.g. Saturday or 25th April')) }}
			{{ $errors->has('renews_on') ? $errors->first('amount', '<p class="error">:message</p>') : '' }}
		</div>
		<div class="field checkbox">
			{{ Form::label('send_reminder', 'Send reminder?') }}
			{{ Form::checkbox('send_reminder', 'true', $bill->send_reminder ? true : false )}}
			{{ $errors->has('send_reminder') ? $errors->first('send_reminder', '<p class="error">:message</p>') : '' }}
		</div>
		<div class="field checkbox">
			{{ Form::label('include_in_totals', 'Include in totals?') }}
			{{ Form::checkbox('include_in_totals', 'true', $bill->include_in_totals ? true : false )}}
			{{ $errors->has('include_in_totals') ? $errors->first('include_in_totals', '<p class="error">:message</p>') : '' }}
		</div>
		<div class="field">
			{{ Form::label('reminder', 'Remind me this many days before') }}
			{{ Form::input('number', 'reminder', Input::old('reminder') ? Input::old('reminder') : $bill->reminder )}}
			{{ $errors->has('reminder') ? $errors->first('reminder', '<p class="error">:message</p>') : '' }}
		</div>
		<div class="field">
			{{ Form::label('comments', 'Comments') }}
			{{ Form::textarea('comments', Input::old('comments') ? Input::old('comments') : $bill->comments ) }}
			{{ $errors->has('comments') ? $errors->first('comments', '<p class="error">:message</p>') : '' }}
		</div>

		<a class="btn" href="/{{ URI::segment(1) }}">Cancel</a>
		
		<input type="submit" class="btn submit" value="Save">
	
	{{ Form::close() }}
	
@endsection