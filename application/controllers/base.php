<?php

class Base_Controller extends Controller {
	/**
	 * Catch-all method for requests that can't be matched.
	 *
	 * @param  string    $method
	 * @param  array     $parameters
	 * @return Response
	 */
	public function __call($method, $parameters)
	{
		return Response::error('404');
	}

	/**
	 * Convert a renewal time supplied as either:
	 *
	 * 1. Day of the week
	 * 2. Day of the month
	 * 3. Day and month of the year
	 *
	 * Return a ISO standard date version of the next renewal date
	 *
	 */
	public function renewal_date_to_date($type, $date)
	{
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
		return $datetime->format('Y-m-d');
	}

	/**
	 * Sort an array of bill objects by their renewal date and add that date to the object	 *
	 *
	 */
	function sort_bills_by_date($bills)
	{
		uasort($bills, function($a, $b){
			$a_date = $this->renewal_date_to_date($a->recurrence, $a->renews_on);
			$b_date = $this->renewal_date_to_date($b->recurrence, $b->renews_on);
			
			$a->renewal_date = $a_date;
			$b->renewal_date = $b_date;

			if ($a_date == $b_date) {
		        return 0;
		    }
		    return ($a_date < $b_date) ? -1 : 1;
		});
		return $bills;
	}
}