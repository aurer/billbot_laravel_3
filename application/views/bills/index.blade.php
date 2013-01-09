@layout('_templates.default')

@section('pagetitle') Your Bills <a class="icon-with-text add" href="/bills/new">Add one</a> @endsection

@section('primary')
	
	<ul class="bills reset">
		@foreach($bills as $item)
			<li class="reminder-{{ _bool($item->send_bills) }}">
				<div class="title">
					<h3>{{ $item->title }} </h3>
					<span class="actions">
						<a title="Edit" class="img-icon" href="/bills/edit/{{ $item->name }}"><img src="/gfx/edit-icon.svg" alt="Edit"></a>
						<a title="Delete" class="img-icon" href="/bills/delete/{{ $item->id }}"><img src="/gfx/delete-icon.svg" alt="Edit"></a>
					</span>
				</div>
				<table class="bill-details">
					<tbody>
						<tr>
							<td title="Charge" class="cost"><b>&pound;{{ $item->amount }}</b></td>
							<td title="Recurrence" class="recurrence">{{ Str::title($item->recurrence) }}</td>
							<td title="Next due date" class="due">
								{{ format_renewal_date($item->recurrence, $item->renews_on) }}
							</td>
							@if( $item->send_reminder )
								<td title="A reminder will be sent by email" class="remider">Remind 
									@if($item->reminder == 0)
										on the day
									@else
										{{ $item->reminder }} {{ Str::plural('day', $item->reminder) }} before
									@endif
								</td>
							@endif
						</tr>
					</tbody>
				</table>
			</li>
		@endforeach
		@if( !$bills )
			<p>It looks like you havent set up any bills yet, you can start by clicking 'Add new' below.</p>
		@endif
	</ul>

	<hr>

	<h2>Totals</h2>
	<table class="layout totals">
		<tr>
			<th class="weekly_total">Weekly's</th>
			<th class="monthly_total">Monthly's</th>
			<th class="yearly_total">Yearly's</th>
		</tr>
		<tr>
			<td class="weekly_total"><i>&pound;</i>{{ $totals['weekly'] }}</td>
			<td class="monthly_total"><i>&pound;</i>{{ $totals['monthly'] }}</td>
			<td class="yearly_total"><i>&pound;</i>{{ $totals['yearly'] }}</td>
		</tr>
		<tr>
			<td class="total_per_month" colspan="2"><i>&pound;</i>{{ $totals['per_month'] }} / <i>&pound;</i>{{ $totals['per_month_plus'] }} <small>per month / including yearly's</small></td>
			<td class="total_per_year"><i>&pound;</i>{{ $totals['per_year'] }} <small>per year</small></td>
		</tr>
	</table>

@endsection