<?php

class Emails_Controller extends Base_Controller
{
	function action_index()
	{

	}

	function action_user_welcome()
	{
		$user = User::order_by( DB::raw('RAND()') )->first();
		$user->hash = Str::random(32);
		return View::make('emails.user_welcome')->with(array('user'=>$user));
	}

	function action_reminder()
	{
		$data['h1'] = "Just a little reminder";
		$data['h2'] = "Here are you upcoming bills";
		$data['message'] = "Lorem ipsum";
		$data['bills'] = Bill::order_by( DB::raw('RAND()') )->take(4)->get();
		return View::make('emails.reminder')->with($data);
	}
}