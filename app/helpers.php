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