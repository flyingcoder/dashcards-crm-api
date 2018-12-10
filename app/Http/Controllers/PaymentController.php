<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function plan()
    {
    	# code...
    }

    public function checkout()
    {
        
    	try {

    		$subscription = 'Gold';
	    	$plan_id = 'prod_DK73slEWcSZ76f'; //gold

	    	if(request()->has('subscription'))
	    		$subscription = request()->subscription;

	    	if(request()->has('plan'))
	    		$plan_id = request()->plan;

	    	$result = auth()->user()
			    		    ->newSubscription('main', $plan_id)
			    		    ->create(request()->token);

	    	return $result;

    	} catch (Exception $e) {

    		return $e;

    	}

    }
}
