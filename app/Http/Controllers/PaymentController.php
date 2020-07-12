<?php

namespace App\Http\Controllers;

use App\Traits\HasConfigTrait;
use App\User;

class PaymentController extends Controller
{
    use HasConfigTrait;

    protected $allowed_stripe_plans = [];

    /**
     * PaymentController constructor.
     */
    public function __construct()
    {
        $config = $this->getConfigByKey('stripe_app_plan', $default = null);
        if ($config && array_key_exists('plans', $config)) {
            $this->allowed_stripe_plans = array_column($config['plans'], 'id');
        }
    }


    /**
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function checkout()
    {
        request()->validate([
            'token' => 'required|string',
            'plan' => 'required|in:'.implode(',', $this->allowed_stripe_plans)
        ]);

        $subscription = request()->subscription ?? 'app_monthly';
        
        $user = User::findOrFail(auth()->user()->id);
        
        if ($user->subscribed($subscription)) {
            abort(433, 'Already subscribed to this subscription type!');
        }

        $user->createAsStripeCustomer();

        $user->newSubscription($subscription,  request()->plan)
              ->create(request()->token);

        return auth()->user();
    }
}
