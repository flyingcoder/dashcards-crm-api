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

if (! function_exists('generateSetPasswordCode')) {

	function generateSetPasswordCode() {

		do {
	        $code = str_random(64);
	        $exist = \App\User::where('code', $code)->first();
	    } while ( $exist );

	    return $code;
	}
}