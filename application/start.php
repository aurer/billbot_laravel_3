<?php

/*
|--------------------------------------------------------------------------
| PHP Display Errors Configuration
|--------------------------------------------------------------------------
|
| Since Laravel intercepts and displays all errors with a detailed stack
| trace, we can turn off the display_errors ini directive. However, you
| may want to enable this option if you ever run into a dreaded white
| screen of death, as it can provide some clues.
|
*/

ini_set('display_errors', 'On');

/*
|--------------------------------------------------------------------------
| Laravel Configuration Loader
|--------------------------------------------------------------------------
|
| The Laravel configuration loader is responsible for returning an array
| of configuration options for a given bundle and file. By default, we
| use the files provided with Laravel; however, you are free to use
| your own storage mechanism for configuration arrays.
|
*/

Laravel\Event::listen(Laravel\Config::loader, function($bundle, $file)
{
	return Laravel\Config::file($bundle, $file);
});

/*
|--------------------------------------------------------------------------
| Register Class Aliases
|--------------------------------------------------------------------------
|
| Aliases allow you to use classes without always specifying their fully
| namespaced path. This is convenient for working with any library that
| makes a heavy use of namespace for class organization. Here we will
| simply register the configured class aliases.
|
*/

$aliases = Laravel\Config::get('application.aliases');

Laravel\Autoloader::$aliases = $aliases;

/*
|--------------------------------------------------------------------------
| Auto-Loader Mappings
|--------------------------------------------------------------------------
|
| Registering a mapping couldn't be easier. Just pass an array of class
| to path maps into the "map" function of Autoloader. Then, when you
| want to use that class, just use it. It's simple!
|
*/

Autoloader::map(array(
	'Base_Controller' => path('app').'controllers/base.php',
));

/*
|--------------------------------------------------------------------------
| Auto-Loader Directories
|--------------------------------------------------------------------------
|
| The Laravel auto-loader can search directories for files using the PSR-0
| naming convention. This convention basically organizes classes by using
| the class namespace to indicate the directory structure.
|
*/

Autoloader::directories(array(
	path('app').'models',
	path('app').'libraries',
));

/*
|--------------------------------------------------------------------------
| Laravel View Loader
|--------------------------------------------------------------------------
|
| The Laravel view loader is responsible for returning the full file path
| for the given bundle and view. Of course, a default implementation is
| provided to load views according to typical Laravel conventions but
| you may change this to customize how your views are organized.
|
*/

Event::listen(View::loader, function($bundle, $view)
{
	return View::file($bundle, $view, Bundle::path($bundle).'views');
});

/*
|--------------------------------------------------------------------------
| Laravel Language Loader
|--------------------------------------------------------------------------
|
| The Laravel language loader is responsible for returning the array of
| language lines for a given bundle, language, and "file". A default
| implementation has been provided which uses the default language
| directories included with Laravel.
|
*/

Event::listen(Lang::loader, function($bundle, $language, $file)
{
	return Lang::file($bundle, $language, $file);
});

/*
|--------------------------------------------------------------------------
| Attach The Laravel Profiler
|--------------------------------------------------------------------------
|
| If the profiler is enabled, we will attach it to the Laravel events
| for both queries and logs. This allows the profiler to intercept
| any of the queries or logs performed by the application.
|
*/

if (Config::get('application.profiler'))
{
	Profiler::attach();
}

/*
|--------------------------------------------------------------------------
| Enable The Blade View Engine
|--------------------------------------------------------------------------
|
| The Blade view engine provides a clean, beautiful templating language
| for your application, including syntax for echoing data and all of
| the typical PHP control structures. We'll simply enable it here.
|
*/

Blade::sharpen();

/*
|--------------------------------------------------------------------------
| Set The Default Timezone
|--------------------------------------------------------------------------
|
| We need to set the default timezone for the application. This controls
| the timezone that will be used by any of the date methods and classes
| utilized by Laravel or your application. The timezone may be set in
| your application configuration file.
|
*/

date_default_timezone_set(Config::get('application.timezone'));

/*
|--------------------------------------------------------------------------
| Start / Load The User Session
|--------------------------------------------------------------------------
|
| Sessions allow the web, which is stateless, to simulate state. In other
| words, sessions allow you to store information about the current user
| and state of your application. Here we'll just fire up the session
| if a session driver has been configured.
|
*/

if ( ! Request::cli() and Config::get('session.driver') !== '')
{
	Session::load();
}

// Custom auth driver
Auth::extend('activeauth', function(){
	return new ActiveAuth;
});

function datestr($datestring, $format='d M Y')
{
	return date($format, strtotime($datestring));
}

function _bool($value)
{
	return ($value) ? 'true' : 'false';
}

Form::macro('submit', function($label)
{
    $str = '<input type="submit"';
    	if($label) $str .= 'value="' . $label.'"';
    $str .= '>';
    return $str;
});

Form::macro('dateselect', function($name, $value=null){

	$selected['year'] = $value ? date('Y', strtotime($value)) : date('Y');
	$selected['month'] = $value ? date('m', strtotime($value)) : date('m');
	$selected['day'] = $value ? date('d', strtotime($value)) : date('d');

	$options['days'] = $options['months'] = $options['years'] = array();

	// Setup days of the month
	for($i=1; $i<32; $i++) {
		$options['days'][str_pad($i, 2, 0, STR_PAD_LEFT)] = str_pad($i, 2, 0, STR_PAD_LEFT);
	}

	// Setup months of the year
	for($i=1; $i<13; $i++) {
		$options['months'][str_pad($i, 2, 0, STR_PAD_LEFT)] = date("F", mktime(0, 0, 0, $i, 10));
	}

	// Setup sensible default years
	$start_year = (int)date('Y')-10;
	$end_year = (int)date('Y')+10;
	for( $i = $start_year; $i<$end_year; $i++ ) {
		$options['years'][$i] = $i;
	}

	$str = Form::select($name . "_day", $options['days'], $selected['day']);
	$str .= Form::select($name . "_month", $options['months'], $selected['month']);
	$str .= Form::select($name . "_year", $options['years'], $selected['year']);
	return $str;
});

function renewal_date_for_display($type, $date)
{
	
	$to_format['weekly'] 	= 'l jS';
	$to_format['monthly'] 	= 'jS M';
	$to_format['yearly'] 	= 'jS M Y';

	$from_format['weekly'] 	= 'd-M-Y';
	$from_format['monthly'] = 'd';
	$from_format['yearly'] 	= 'z';

	$interval['weekly'] 	= 'P7D';
	$interval['monthly'] 	= 'P1M';
	$interval['yearly'] 	= 'P1Y';

	if($type==='weekly'){
		$day = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
		$date = date('d-M-Y', strtotime($day[$date]));
	}	

	$datetime = DateTime::createFromFormat($from_format[$type], $date);

	if( $datetime->format('Y-m-d') < date('Y-m-d') ) {
		$datetime->add( new DateInterval($interval[$type]) );
	}
	return $datetime->format($to_format[$type]);
}

function renewal_date_for_input($type, $date)
{
	
	$to_format['weekly'] 	= 'l';
	$to_format['monthly'] 	= 'jS';
	$to_format['yearly'] 	= 'jS M';

	$from_format['weekly'] 	= 'd-M-Y';
	$from_format['monthly'] = 'd';
	$from_format['yearly'] 	= 'z';

	$interval['weekly'] 	= 'P7D';
	$interval['monthly'] 	= 'P1M';
	$interval['yearly'] 	= 'P1Y';

	if($type==='weekly'){
		$day = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
		$date = date('d-M-Y', strtotime($day[$date]));
	}	

	$datetime = DateTime::createFromFormat($from_format[$type], $date);

	if( $datetime->format('Y-m-d') < date('Y-m-d') ) {
		$datetime->add( new DateInterval($interval[$type]) );
	}
	return $datetime->format($to_format[$type]);
}