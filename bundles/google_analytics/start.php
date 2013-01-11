<?php

function google_analytics()
{
	if( Config::get('google_analytics::google_analytics.tracking_code') ){
		return View::make('google_analytics::script');
	}
}