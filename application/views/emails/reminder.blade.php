@layout('_templates.email')
@section('pagetitle') {{ $h1 }} @endsection
@section('primary')
	<h2>{{ $h2 }}</h2>
	<hr style="border:none; border-top: 1px solid #333">
	@foreach($bills as $bill)
		<h3>{{ $bill->title }}</h3>
	@endforeach
@endsection