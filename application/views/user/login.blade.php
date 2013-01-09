@layout('_templates.login')

@section('pagetitle') Login @endsection

@section('primary')
	@if( Auth::check() )
		You are already logged in, no need to be here.
	@endif
	@include('_includes.login')
@endsection