<?php
	Asset::add('main', 'css/compiled/main.css');
	Asset::add('jquery', 'http://code.jquery.com/jquery.min.js');
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>{{ Config::get('application.name') }} / @yield('pagetitle')</title>
	@yield('head')
	{{ Asset::styles() }}
</head>