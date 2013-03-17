<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
*/

// Require Auth unless being run from the CLI
if( !Request::cli() ){
	if( Auth::check() ){
		Route::get('/', 'bills@index');
	} else {
		Route::get('/', 'home@index');
	}
}

Route::get('login', function(){
	return View::make('user.login');
});

Route::controller( Controller::detect() );

/*
|--------------------------------------------------------------------------
| Route Filters
|--------------------------------------------------------------------------
*/

Route::filter('before', function()
{
	if( Input::get('__username') && Input::get('__password') ){
		if ( !Auth::attempt(array('username' => Input::get('__username'), 'password' => Input::get('__password') ) ) ){
			Session::flash('login-message', 'Sorry that login was not recognised');
			Input::flash('only', array('__username'));
		}
		return Redirect::to( URI::current() );
	}

	if( Input::get('logout') == 'true' ){
		Auth::logout();
		return Redirect::to( URI::current() );
	}
	
	$allowed = array('user', 'user/join', 'user/thanks', 'user/confirm', 'user/reset', 'user/reset_sent', 'user/reset_confirm', 'user/reset_complete', 'login', 'docs*');
	$restricted = true;
	foreach($allowed as $pattern){
		if( substr($pattern, -1) === '*' ){
			if( URI::segment(1) === substr($pattern, 0, -1) ){
				$restricted = false;
				break;
			}
		}
		elseif( $pattern === URI::current() ){
			$restricted = false;
			break;
		}
	}
	if( $restricted AND !Auth::check() ){
		return View::make('user.login');
	}
});

Route::filter('after', function($response)
{
	// Do stuff after every request to your application...
});

Route::filter('csrf', function()
{
	if (Request::forged()) return Response::error('500');
});


/*
|--------------------------------------------------------------------------
| Application 404 & 500 Error Handlers
|--------------------------------------------------------------------------
*/

Event::listen('404', function()
{
	return Response::error('404');
});

Event::listen('500', function()
{
	return Response::error('500');
});