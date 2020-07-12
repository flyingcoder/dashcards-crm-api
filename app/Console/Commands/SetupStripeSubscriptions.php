<?php

namespace App\Console\Commands;

use App\Configuration;
use App\Traits\HasConfigTrait;
use App\Traits\StripeTrait;
use Illuminate\Console\Command;
use Stripe\Product;
use Stripe\Stripe;

class SetupStripeSubscriptions extends Command
{
    use HasConfigTrait, StripeTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:stripe-subscription';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup stripe subscriptions for this app';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle()
    {
        $stripe_plan = Configuration::where('key', 'stripe_app_plan')->exists();
            
        if (!$stripe_plan) {
            $product = $this->createProduct();
            if ($product) {

                $params = [
                    [
                        'product' => $product->id,
                        'recurring' => [
                                'interval' => 'month'
                            ],
                        'currency' => 'usd',
                        'unit_amount' => 1499,
                        'nickname' => 'Basic',
                        'metadata' => [
                                'discount_description' => '($290/year - You save ~$9)',
                                'plan_description' => 'A limited number of reports to monitor up to 1 locations'
                            ] 
                    ],
                    [
                        'product' => $product->id,
                        'recurring' => [
                                'interval' => 'month'
                            ],
                        'currency' => 'usd',
                        'unit_amount' => 2499,
                        'nickname' => 'Pro',
                        'metadata' => [
                                'discount_description' => '($290/year - You save ~$9)',
                                'plan_description' => 'A limited number of reports to monitor up to 10 locations'
                            ] 
                    ],
                    [
                        'product' => $product->id,
                        'recurring' => [
                                'interval' => 'month'
                            ],
                        'currency' => 'usd',
                        'unit_amount' => 4999,
                        'nickname' => 'Business',
                        'metadata' => [
                                'discount_description' => '($290/year - You save ~$9)',
                                'plan_description' => 'A limited number of reports to monitor up to unlimted locations'
                            ] 
                    ],
                ];
                foreach ($params as $param) {
                    $this->createPlanPrice($param);
                }

                $product->plans = $this->getPlanPrice(['product' => $product->id]);

                $config = Configuration::create([
                    'key' => 'stripe_app_plan',
                    'type' => 'object',
                    'value' => $this->storeValue('object', $product)
                ]);
            }

        }
        echo "Done!";
    }

    /**
     * @return Product
     */
    private function createProduct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
        return Product::create([
            'name' => 'App Subscriptions',
            'description' => 'App subscription via Stripe',
            'images' => [
                config('app.url').'/img/logo/buzzooka-mini.png',
                config('app.url').'/img/logo/color-logo.png'
            ]
        ]);
    }
}
