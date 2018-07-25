<?php

use Illuminate\Http\Request;

function parseSearchParam(Request $request) {

	if(!$request->has('sort'))
		return false;
	
    $sort = $request->sort;

    $params = explode('|', $sort);

    if(count($params) == 1)
    	return false;

    return [$params[0], $params[1]];
}