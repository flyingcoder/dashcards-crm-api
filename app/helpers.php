<?php

use Illuminate\Http\Request;

if (! function_exists('parseSearchParam')) {
	
	function parseSearchParam(Request $request) {

		if(!$request->has('sort'))
			return false;
		
	    $sort = $request->sort;

	    $params = explode('|', $sort);

	    if(count($params) == 1)
	    	return false;

	    return [$params[0], $params[1]];
	}
}

if (! function_exists('secondsForHumans')) {

	function secondsForHumans($seconds, $display_sec = false) {

		$hours = floor($seconds / 3600);

		$minutes = floor(($seconds / 60) % 60);
		
		$seconds = $seconds % 60;

		$formatted =  $hours."h ".$minutes."m ";

		if($display_sec)
			$formatted .= $seconds."s";

		return $formatted;
	}

}
if (! function_exists('parseSeconds')) {
	function parseSeconds($given_seconds, $sep = ":")
    {
		$hours = floor($given_seconds / 3600);
		$minutes = floor(($given_seconds / 60) % 60);
		$seconds = $given_seconds % 60;

		$data    = new stdClass(); 
		$data->total_seconds = $given_seconds;
		$data->hrs = (int) $hours;
		$data->min = (int) $minutes;
		$data->sec = (int) $seconds;
		$data->readable = secondsForHumans($seconds);
		$data->format = sprintf("%02d%s%02d%s%02d", $hours, $sep, $minutes, $sep, $seconds);
		if ($hours > 99) {
			$data->format = sprintf("%d%s%02d%s%02d", $hours, $sep, $minutes, $sep, $seconds);
		}

		return $data;
    }
}
if (! function_exists('natural_language_join')) {

	function natural_language_join(array $list, $conjunction = 'and') {
	  	$last = array_pop($list);
	  	if ($list) {
	    	return implode(', ', $list) . ' ' . $conjunction . ' ' . $last;
	  	}
	  	return $last;
	}
}

if (! function_exists('random_avatar')) {

	function random_avatar($gender = null) {
	  	$avatar = [
	  		'male' => config('app.url').'/img/members/alfred.png',
	  		'female' => config('app.url').'/img/members/selena.png'
	  	];
	  	if (!is_null($gender) && array_key_exists(strtolower($gender), $avatar)) {
	  		return $avatar[$strtolower($gender)];
	  	}
  		return $avatar[array_rand($avatar, 1)];	
	}
}

if (! function_exists('stripos_arr')) {
	function stripos_arr($haystack, $needle) {
	    if(!is_array($haystack)) $haystack = array($haystack);
	    foreach($haystack as $index => $what) {
	    	$pos = stripos($what, $needle);
	        if($pos !== false) return [$index, $pos];
	    }
	    return false;
	}
}