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
    	$subscription = 'Monthly';
        $plan_id = 'plan_E8UgXycLjHZYJ4'; //gold

        if(request()->has('subscription'))
            $subscription = request()->subscription;

        if(request()->has('plan'))
            $plan_id = request()->plan;

        $result = auth()->user()
                        ->newSubscription($subscription, $plan_id)
                        ->create(request()->token);

        return $result;

    }
}
