<?php

use Illuminate\Http\Request;

function parseSearchParam(Request $request) {

	if(!$request->has('sort'))
		return false;
	
    $sort = $request->sort;
    $params = explode('|', $sort);

    return [$params[0], $params[1]];
}