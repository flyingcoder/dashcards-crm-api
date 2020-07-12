<?php

namespace App\Http\Controllers;

use App\Events\GlobalEvent;
use App\Invoice;
use App\Traits\HasConfigTrait;
use App\Traits\StripeTrait;
use App\User;
use Exception;
use Stripe\Account;
use Stripe\OAuth;
use Stripe\Plan;
use Stripe\Stripe;

class StripeController extends Controller
{
    use HasConfigTrait, StripeTrait;

    protected $apiVersion = "2020-03-02"; //2019-05-16

    protected $application_fee_percent = 0.005;

    /**
     * StripeController constructor.
     */
    public function __construct()
    {
        Stripe::setApiVersion($this->apiVersion);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Stripe\Error\Api
     */
    public function getStripePlans()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
        $fromStripe = Plan::all();

        return response()->json($fromStripe, 200);
    }

    /**
     *
     */
    public function syncPlans()
    {
        $product = $this->getConfig('stripe_app_plan');
        if ($product) {
            $product_value = $this->castValue($product);
            $stripePlans = $this->getPlanPrice(['product' => $product_value->id]);
            $product_value->plans = $stripePlans;

            $product->value = $this->storeValue('object', $product_value);
            $product->save();
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Stripe\Error\Api
     */
    public function createStripePlans()
    {
        request()->validate([
            'nickname' => 'required|string',
            'amount' => 'required|numeric|min:1',
            'short_description' => 'required|string',
            'long_description' => 'required|string',
            'currency' => 'required|string'
        ]);

        $selectedPlans = $this->getConfigByKey('stripe_app_plan', false);
        if (!$selectedPlans) {
            abort(433, 'Unable to create plan. No product found!');
        }

        $params = [
            'product' => $selectedPlans->id,
            'recurring' => [
                'interval' => 'month'
            ],
            'currency' => strtolower(request()->currency),
            'unit_amount' => request()->amount * 100,
            'nickname' => request()->nickname,
            'metadata' => [
                'discount_description' => request()->short_description,
                'plan_description' => request()->long_description
            ]
        ];

        $price = $this->createPlanPrice($params);

        $this->syncPlans();

        broadcast(new GlobalEvent(array_merge(['type' => 'configs'], $this->getAllConfigs())));

        return $this->getStripePlans();
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Stripe\Error\Api
     */
    public function updateStripePlans()
    {
        request()->validate([
            'id' => 'required|string',
            'nickname' => 'required|string',
            'amount' => 'required|numeric|min:1',
            'short_description' => 'required|string',
            'long_description' => 'required|string',
            'currency' => 'required|string'
        ]);

        $params = [
            // 'currency' => strtolower(request()->currency),
            // 'unit_amount' => request()->amount * 100,
            'nickname' => request()->nickname,
            'metadata' => [
                'discount_description' => request()->short_description,
                'plan_description' => request()->long_description
            ]
        ];

        $price = $this->updatePlanPrice(request()->id, $params);

        $this->syncPlans();

        broadcast(new GlobalEvent(array_merge(['type' => 'configs'], $this->getAllConfigs())));

        return $this->getStripePlans();
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStripeAccount()
    {
        $user = User::find(auth()->user()->id);
        $stripe_info = $user->getMeta('stripe_info', false);

        if ($stripe_info) {
            return response()->json($stripe_info, 200);
        }
        return response()->json([], 500);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function disconnectFromStripe()
    {
        $user = User::findOrFail(auth()->user()->id);

        Stripe::setApiKey(config('services.stripe.secret'));

        try {

            $disconnect = OAuth::deauthorize([
                'client_id' => config('services.stripe.client_id'),
                'stripe_user_id' => $user->stripe_id
            ]);


            $user->stripe_id = null;
            $user->save();

            $user->removeMeta('stripe_info');

            return response()->json(['message' => 'Successfully disconnect Stripe Account'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to disconnect Stripe Account', $e->getMessage()], 500);
        }

    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function connectToStripe()
    {

        request()->validate(['code' => 'required|string']);

        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $response = OAuth::token([
                'grant_type' => 'authorization_code',
                'code' => trim(request()->code),
            ]);

            $user = User::find(auth()->user()->id);
            $user->stripe_id = $response->stripe_user_id;
            $user->save();

            $info = [
                'access_token' => $response->access_token,
                'refresh_token' => $response->refresh_token,
                'token_type' => $response->token_type,
                'stripe_publishable_key' => $response->stripe_publishable_key,
                'stripe_user_id' => $response->stripe_user_id,
                'scope' => $response->scope,
            ];

            $account = Account::retrieve($response->stripe_user_id);
            if ($account) {
                $info['type'] = $account->type;
                $info['email'] = $account->email;
                $info['charges_enabled'] = $account->charges_enabled;
            }

            $user->setMeta('stripe_info', $info);

            return response()->json($info, 200);

        } catch (\Stripe\Error\OAuth\InvalidGrant $e) {
            return response()->json(['message' => 'Invalid authorization code: ' . request()->code], 400);
        } catch (Exception $e) {
            return response()->json(['message' => 'An unknown error occurred.'], 500);
        }
    }

    /**
     * Create payment intent for given invoice id
     * Integer $id
     */
    public function createPaymentIntent($id)
    {
        $company = auth()->user()->company();

        $invoice = $company->invoices()->where('invoices.id', '=', $id)->firstOrFail();

        if ($invoice) {
            $invoice = Invoice::findOrFail($invoice->id);
            $invoice->load('billedTo', 'billedFrom');

            if (!$invoice->billedFrom->stripe_id) {
                return response()->json([
                    'message' => "User " . $invoice->billedFrom->fullname . " doesn't have Stripe payment connected.",
                    'invoice' => $invoice
                ], 200);
            }

            $payment_intent = $invoice->getMeta('payment_intent', false);

            if (!$payment_intent) {

                Stripe::setApiKey(config('services.stripe.secret'));

                $payment_intent = \Stripe\PaymentIntent::create([
                    'payment_method_types' => ['card'],
                    'amount' => $invoice->total_amount * 100, //to cents
                    'currency' => 'usd',
                    'application_fee_amount' => ($invoice->total_amount * $this->application_fee_percent) * 100, //take 5% of total then to cents
                    'metadata' => [
                        'platform_billedTo_id' => $invoice->billedTo->id,
                        'platform_billedFrom_id' => $invoice->billedFrom->id,
                        'platform_invoice_id' => $invoice->id
                    ],
                    'receipt_email' => $invoice->billedTo->email
                ], ['stripe_account' => $invoice->billedFrom->stripe_id]);

                $invoice->setMeta('payment_intent', $payment_intent);
            }

            return response()->json([
                'payment_intent' => $payment_intent,
                'invoice' => $invoice,
                'stripe_account' => $invoice->billedFrom->stripe_id
            ], 200);
        }

        return response()->json(['message' => "No invoice found for invoice id : " . $id], 404);
    }
}