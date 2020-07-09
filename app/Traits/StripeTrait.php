<?php

namespace App\Traits;

use GuzzleHttp\Client;

trait StripeTrait 
{
	protected $api_base = 'https://api.stripe.com/v1/';


	public function sendApiRequest($api_path, $request_type = 'GET' ,$params = [])
	{
		try {
			$apikey = config('services.stripe.secret');
			$client = new Client();
			$res = $client->request($request_type, $this->api_base.$api_path, [
			    'headers' => [
			        'Accept'     => 'application/json',
			    	'Authorization' => 'Bearer '.$apikey,
			    ],
			    'body' => http_build_query($params)
			]);
			
			return  json_decode($res->getBody()->getContents(), true);

		} catch (\Exception $e) {
			return $e->getMessage(); 
		}
	}

	public function createPlanPrice($params) {
		$price = $this->sendApiRequest('prices', 'POST', $params);
		return $price;
	}

	public function updatePlanPrice($product_id, $params) {
		$price = $this->sendApiRequest('prices/'.$product_id, 'POST', $params);
		return $price;
	}

	public function getPlanPrice($params) {
		$response = $this->sendApiRequest('prices', 'GET', $params);
		
		return array_key_exists('data', $response) ? $response['data'] : [];
	}
}