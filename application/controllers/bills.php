<?php

class Bills_Controller extends Base_Controller
{
	public $restful = true;
	private static $validation_rules = array(
	    'title'  => 'required|unique_bill_title',
	    'amount' => 'required',
	    'renews_on' => 'required',
	);

	public function get_index()
	{
		$data['bills'] 						= Auth::user()->bill()->get();
		$data['totals']['weekly'] 			= Auth::user()->bill()->where_include_in_totals(1)->where_recurrence('weekly')->sum('amount')	;
		$data['totals']['monthly']			= Auth::user()->bill()->where_include_in_totals(1)->where_recurrence('monthly')->sum('amount')	;
		$data['totals']['yearly'] 			= Auth::user()->bill()->where_include_in_totals(1)->where_recurrence('yearly')->sum('amount')	;
		$data['totals']['per_month']		= ($data['totals']['weekly'] * 4) + $data['totals']['monthly']	;
		$data['totals']['per_month_plus']	= $data['totals']['per_month'] + ($data['totals']['yearly'] / 12)	;
		$data['totals']['per_year'] 		= ($data['totals']['per_month'] * 12) + ($data['totals']['yearly'])	;
		
		// Sort bills by due date and add 'due_in'
		$data['bills'] = Bill::sort_bills_by_date($data['bills']);
		foreach ($data['bills'] as $bill) {
			$date2 = new DateTime($bill->renewal_date);
			$date1 = new DateTime(date("Y-m-d"));
			$interval = $date1->diff($date2);
			$bill->due_in = $interval->format('%a');
		}

		// Format the totals e.g. 00.00
		foreach ($data['totals'] as $key => $val) {
			$data['totals'][$key] = number_format($val, 2);
		}

		return View::make('bills.index')->with($data);
	}

	public function get_new()
	{
		Asset::add('bill-forms', 'js/bill-forms.js', 'jquery');
		return View::make('bills.new');
	}

	public function post_new()
	{
		
		Validator::register('unique_bill_title', function($attribute, $value, $parameters)
		{
		    return !Bill::where_user_id(Auth::user()->id)->where_name( Str::slug($value) )->first();
		});
		$messages = array('unique_bill_title', "You've already used this :attribute");

		$input = Input::all();
		$validation = Validator::make($input, self::$validation_rules);
		if( $validation->fails() ){
			Input::flash();
			return Redirect::to( URI::current() )->with_errors($validation);
		}

		$bill = new Bill;
		$bill->user_id = Auth::user()->id;
		$bill->title = Input::get('title');
		$bill->name = Str::slug( Input::get('title') );
		$bill->amount = Input::get('amount');
		$bill->recurrence = Input::get('recurrence');
		if( Input::get('recurrence') === 'weekly' ){
			$bill->renews_on = date('N', strtotime( Input::get('renews_on') ) );
		} elseif( Input::get('recurrence') === 'monthly' ) {
			$bill->renews_on = date('d', strtotime( Input::get('renews_on') ) );
		} else {
			$bill->renews_on = date('z', strtotime( Input::get('renews_on') ) );
		}
		$bill->send_reminder = Input::get('send_reminder') ? true : false;
		$bill->include_in_totals = true;
		$bill->reminder = Input::get('reminder');
		$bill->comments = Input::get('comments');
		$bill->save();
		
		return Redirect::to( 'bills' );
	}

	public function get_delete($id)
	{
		$bill = Bill::where_id($id)->first();
		if($bill){
			$bill->delete();
		}
		return Redirect::back();
	}

	public function get_edit($name=null)
	{
		Asset::add('bill-forms', 'js/bill-forms.js', 'jquery');
		$data = Auth::user()->bill()->where_name($name)->first();
		if(!$data) return Response::error('404');
		return View::make('bills.edit')->with('bill', $data);
	}

	public function post_edit($name)
	{
		$bill = Auth::user()->bill()->where_name($name)->first();
		if(!$bill) return Response::error('404');
		
		$input = Input::all();
		self::$validation_rules['title'] = 'required';
		$validation = Validator::make($input, self::$validation_rules);
		if( $validation->fails() ){
			Input::flash();
			return Redirect::to( URI::current() )->with_errors($validation);
		}

		$bill->title = Input::get('title');
		$bill->name = Str::slug( Input::get('title') );
		$bill->amount = Input::get('amount');
		$bill->recurrence = Input::get('recurrence');
		if( Input::get('recurrence') === 'weekly'){
			$bill->renews_on = date('w', strtotime( Input::get('renews_on') ) );
		} elseif( Input::get('recurrence') === 'monthly' ) {
			$bill->renews_on = date('d', strtotime( Input::get('renews_on') . " " . date('F') ) );
		} else {
			$bill->renews_on = date('z', strtotime( Input::get('renews_on') ) );
		}
		$bill->send_reminder = Input::get('send_reminder') ? true : false;
		$bill->include_in_totals = Input::get('include_in_totals') ? true : false;
		$bill->reminder = Input::get('reminder');
		$bill->comments = Input::get('comments');
		$bill->save();
		
		return Redirect::to( 'bills' );
	}
}
