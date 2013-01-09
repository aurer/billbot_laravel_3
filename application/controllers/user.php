<?php

class User_Controller extends Base_Controller
{
	public $restful = true;

	// Users profile
	public function get_index()
	{
		$data = Auth::user();
		return View::make('user.profile')->with( array('data' => $data) );
	}

	public function post_index()
	{
		$input = Input::all();
		$rules = array(
		    'username'  => 'required',
		    'email'  	=> 'required|email|unique:users,email,' . Auth::user()->id,
		);
		if( Input::get('password') || Input::get('password_confirm') ){
			$rules['password'] 			= 'required';
		    $rules['password_confirm']  = 'required|same:password';
		}
		
		$validation = Validator::make($input, $rules);
		if( $validation->fails() ){
			Input::flash();
			return Redirect::to( URI::current() )->with_errors($validation);
		}

		$user = Auth::user();
		$user->forename = Input::get('forename');
		$user->surname  = Input::get('surname');
		$user->username = Input::get('username');
		$user->email 	= Input::get('email');
		if( Input::get('password') || Input::get('password_confirm') ){
			$user->password = Hash::make(Input::get('password'));
		}
		$user->save();
		return Redirect::to( URI::current() )->with('success', 'Your details have been updated');
	}

	// Join form
	public function get_join()
	{
		return View::make('user.new');
	}

	// Thanks for joining
	public function get_thanks()
	{
		return View::make('user.thanks');
	}

	// Confirm registration via key
	public function get_confirm()
	{
		$user = User::where_active(false)->where_hash(Input::get('hash'))->first();
		if($user){
			$user->hash = '';
			$user->active = true;
			$user->save();
			Auth::login($user->id);
			return Redirect::home();
		}
		 return Redirect::to('user/thanks')->with('error', 'The activation key you provided was not recognised or has expired.');
	}

	// Add new user
	public function post_join()
	{
		$input = Input::all();
		$rules = array(
		    'username'  		=> 'required',
		    'email'  			=> 'required|email|unique:users',
		    'password'  		=> 'required',
		    'password_confirm' 	=> 'required|same:password',
		);
		
		$validation = Validator::make($input, $rules);
		if( $validation->fails() ){
			Input::flash();
			return Redirect::to( URI::current() )->with_errors($validation);
		}

		$hash = Str::random(32);
		$user = new User;
		$user->username = Input::get('username');
		$user->email 	= Input::get('email');
		$user->password = Hash::make(Input::get('password'));
		$user->active 	= false;
		$user->hash 	= $hash;
		$user->save();
		
		if( static::send_welcome_email($user) ){
			return Redirect::to( URI::segment(1) . '/thanks' );
		}

		return Redirect::to( URI::segment(1) );
	}

	public function get_reset()
	{
		return View::make('user.reset');
	}

	public function get_reset_sent()
	{
		return View::make('user.reset-sent');
	}

	public function get_reset_complete()
	{
		return View::make('user.reset-complete');
	}

	public function post_reset()
	{
		$input = Input::all();
		$rules = array(
			'email' => 'required|email|exists:users'
		);

		$validation = Validator::make($input, $rules);
		if( $validation->fails() ){
			Input::flash();
			return Redirect::to( URI::current() )->with_errors($validation);
		}

		$hash = Str::random(32);
		$user = User::where_email( Input::get('email') )->first();
		
		if(!$user){
			return Redirect::to( URI::current() )->with('error', "Sorry we couldn't find that email address in our records.");
		}

		$user->hash = $hash;
		$user->save();


		if( static::send_reset_email($user) ){
			return Redirect::to( URI::segment(1) . '/reset_sent' );
		}

		return Redirect::to( URI::segment(1) );		
	}

	// Confirm registration via key
	public function get_reset_confirm()
	{
		$user = User::where_hash(Input::get('hash'))->first();
		if($user){
			return View::make('user.reset-confirm');
		} else {
			Session::flash('error', "Sorry but your reset token is invalid or has expired, please try again.");
			return View::make('user.reset-confirm');
		}
	}

	public function post_reset_confirm(){

		$input = Input::all();
		$rules = array(
			'password' => 'required',
			'password_confirm' => 'required|same:password',
		);
		$validation = Validator::make($input, $rules);
		if( $validation->fails() ){
			Input::flash();
			return Redirect::to( URI::current() . '?hash=' . Input::get('hash') )->with_errors($validation);
		}
		
		$user = User::where_hash(Input::get('hash'))->first();
		if($user){
			$user->hash = '';
			$user->active = true;
			$user->password = Hash::make( Input::get('password') );
			$user->save();
			Auth::login($user->id);
			return Redirect::to('user/reset_complete');
		}
		return Redirect( URI::current() )->with('error', "Sorry but your reset token is invalid or has expired, please try again.");
	}


	// Send a welcome email when joining
	private function send_welcome_email($user)
	{
		if($user){
			$to = $user->email;
			$from = "Billbot <billbot@bills.aurer.co.uk>";
			$subject = "Welcome to Billbot";
			$message = View::make('emails.user_welcome')->with( array( 'user'=>$user) );
			$headers  = "From: " . $from . "\r\n"; 
            $headers .= "Content-type: text/html\r\n";
			return mail($to, $subject, $message, $headers);
		}
		return false;
	}

	// Send a ressewt password email to user
	private function send_reset_email($user)
	{
		if($user){
			$to = $user->email;
			$from = "Billbot <billbot@bills.aurer.co.uk>";
			$subject = "You requested a password reset";
			$message = View::make('emails.user_reset')->with( array( 'user'=>$user) );
			$headers  = "From: " . $from . "\r\n"; 
            $headers .= "Content-type: text/html\r\n";
			return mail($to, $subject, $message, $headers);
		}
		return false;
	}
}