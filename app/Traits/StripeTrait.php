<?php

namespace App\Traits;

use Exception;
use GuzzleHttp\Client;

trait StripeTrait
{
    protected $api_base = 'https://api.stripe.com/v1/';


    /**
     * @param $api_path
     * @param string $request_type
     * @param array $params
     * @return mixed|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendApiRequest($api_path, $request_type = 'GET', $params = [])
    {
        try {
            $apikey = config('services.stripe.secret');
            $client = new Client();
            $res = $client->request($request_type, $this->api_base . $api_path, [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . $apikey,
                ],
                'body' => http_build_query($params)
            ]);

            return json_decode($res->getBody()->getContents(), true);

        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * @param $params
     * @return mixed|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function createPlanPrice($params)
    {
        return $this->sendApiRequest('prices', 'POST', $params);
    }

    /**
     * @param $product_id
     * @param $params
     * @return mixed|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function updatePlanPrice($product_id, $params)
    {
        return $this->sendApiRequest('prices/' . $product_id, 'POST', $params);
    }

    /**
     * @param $params
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getPlanPrice($params)
    {
        $response = $this->sendApiRequest('prices', 'GET', $params);

        return array_key_exists('data', $response) ? $response['data'] : [];
    }
}