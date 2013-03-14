<?php
	Asset::add('main', 'css/compiled/main.css');
	Asset::add('font-awesome', 'font-awesome/css/font-awesome.min.css');
	Asset::add('jquery', 'js/jquery-1.8.0.min.js');
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="initial-scale=1.0">
	<title>{{ Config::get('application.name') }} / @yield('pagetitle')</title>
	@yield('head')
	{{ Asset::styles() }}
</head>