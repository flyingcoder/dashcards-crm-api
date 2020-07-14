<?php

namespace App\Http\Controllers;

use App\Backlog;
use App\Events\GlobalEvent;
use App\Invoice;
use App\Traits\HasConfigTrait;
use App\Traits\StripeTrait;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Stripe\Error\SignatureVerification;
use Stripe\Webhook;
use Tolawho\Loggy\Facades\Loggy;

class StripeWebhookController extends Controller
{
    use HasConfigTrait, StripeTrait;

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listen(Request $request)
    {
        // You can find your endpoint's secret in your webhook settings
        $endpoint_secret = config('services.stripe.webhooksecret');

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
            $event = Webhook::constructEvent($payload, $sig_header, $endpoint_secret);
            $method = 'handle' . ucwords(Str::camel(str_replace('.', " ", $event->type)));

            // Loggy::write('stripe', json_encode($event));

            if (method_exists($this, $method)) {
                Backlog::create([
                    'event_id' => $event->id,
                    'account' => $event->account,
                    'event_type' => $event->type,
                    'livemode' => $event->livemode,
                    'data' => (object)@$event->data->object,
                ]);

                return $this->{$method}($event);
            } else {
                return response()->json(['message' => 'No handler found',], 200);
            }
        } catch (\UnexpectedValueException $e) {
            return response()->json(['message' => 'Invalid payload'], 200);
        } catch (SignatureVerification $e) {
            return response()->json(['message' => 'Invalid signature'], 200);
        }

    }

    /**
     * payment_intent.succeeded
     */
    public function handlePaymentIntentSucceeded($event)
    {
        $intent = $event->data->object;
        if ($intent && isset($intent->metadata->platform_invoice_id)) {
            $invoice = Invoice::findOrFail((int)$intent->metadata->platform_invoice_id);

            if ($invoice && $intent->status == 'succeeded') {
                $invoice->status = 'paid';
                $props = $invoice->props;
                $props['payment_intent_id'] = $intent->id;
                $props['application_fee_amount'] = $intent->application_fee_amount;
                $props['receipt_url'] = @$intent->charges->data[0]->receipt_url;
                $props['data'] = @$intent->charges->data[0];
                $invoice->props = $props;
                $invoice->save();
            }
        }

        return response()->json(['success' => true], 200);
    }

    public function handlePaymentIntentCanceled($event)
    {
        $intent = $event->data->object;
        if ($intent) {
            Loggy::write('stripe', json_encode([$intent->id]));
            $invoice = Invoice::where('status', '<>', 'paid')
                ->whereMeta('payment_intent', 'like', '%' . $intent->id . '%')
                ->first();
            if ($invoice) {
                $invoice->removeMeta('payment_intent');
            }
        }
        return response()->json(['success' => true], 200);
    }

    /**
     * account.external_account.deleted
     */
    public function handleAccountExternalAccountDeleted($event)
    {
        //same with application deauthorize
        return $this->handleAccountApplicationDeauthorized($event);
    }

    /**
     * account.application.deauthorized
     */
    public function handleAccountApplicationDeauthorized($event)
    {
        if (isset($event->account)) {
            //Get all users that used this stripe accounts
            $binded_accounts = User::where('stripe_id', '=', $event->account)->get();
            if (!$binded_accounts->isEmpty()) {
                foreach ($binded_accounts as $key => $user) {
                    if ($user->hasMeta('stripe_info')) {
                        $user->removeMeta('stripe_info');
                    }
                    $user->stripe_id = null;
                    $user->save();
                }
            }
        }
        return response()->json(['success' => true], 200);
    }

    /**
     * price.created
     */
    public function handlePriceCreated($event)
    {
        $product = $this->getConfig('stripe_app_plan');
        if ($product) {
            $product_value = $this->castValue($product);
            $stripePlans = $this->getPlanPrice(['product' => $product_value->id]);
            $product_value->plans = $stripePlans;

            $product->value = $this->storeValue('object', $product_value);
            $product->save();

            broadcast(new GlobalEvent(array_merge(['type' => 'configs'], $this->getAllConfigs())));
        }
        return response()->json(['success' => true], 200);
    }

    /**
     * price.updated
     */
    public function handlePriceUpdated($event)
    {
        return $this->handlePriceCreated($event);
    }

    /**
     * price.deleted
     */
    public function handlePriceDeleted($event)
    {
        return $this->handlePriceCreated($event);
    }
}