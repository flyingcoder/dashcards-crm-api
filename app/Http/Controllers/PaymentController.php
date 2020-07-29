<?php

namespace App\Http\Controllers;

use App\Events\CompanyEvent;
use App\Traits\HasConfigTrait;
use App\User;
use Exception;

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
            $this->allowed_stripe_plans = array_column($config->plans, 'id');
        }
    }


    /**
     * @return \Illuminate\Contracts\Auth\Authenticatable|\Illuminate\Http\JsonResponse|null
     */
    public function checkout()
    {
        request()->validate([
            'token' => 'required|string',
            'plan' => 'required|in:' . implode(',', $this->allowed_stripe_plans)
        ]);
        try {
            $user = User::findOrFail(auth()->user()->id);

            $subscription = request()->subscription ?? 'app_monthly';
            if ($user->subscribed($subscription)) {
                throw new Exception('Already subscribed to this subscription type!');
            }

            $user->createAsStripeCustomer();
            $user->newSubscription($subscription, request()->plan)->create(request()->token);

            $company = $user->company();
            $props = $company->others;
            $props['company_subscribed'] = true;
            $props['company_subscriber'] = $user->id;
            $company->others = $props;
            $company->save();

            broadcast(new CompanyEvent($company->id, array_merge(['type' => 'configs'], $company->toArray())));

            return auth()->user();
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 522);
        }
    }
}
