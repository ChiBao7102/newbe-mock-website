<?php

namespace App\Traits;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

trait ConsumeExternalService
{
    /**
     * Send request to any service
     * @param $method
     * @param $requestUrl
     * @param array $formParams
     * @param array $queryParams
     * @param array $headers
     * @return string
     */
    public function performRequest($method, $requestUrl, $formParams = [], $queryParams = [], $headers = [])
    {
        $client = new Client([
            'base_uri'  =>  $this->baseUri,
        ]);

        if(isset($this->secret))
        {
            $headers['Accept'] = 'application/json';
        }
        $response = $client->request($method, $requestUrl, [
            'form_params' => $formParams,
            'query' => $queryParams,
            'headers'     => $headers,
        ]);
        return $response->getBody()->getContents();
    }

    public function performRequestJson($method, $requestUrl, $jsonData = [], $headers = [])
    {
        $client = new Client([
            'base_uri' => $this->baseUri,
        ]);

        if (isset($this->secret)) {
            $headers['Authorization'] = $this->secret;
        }
        $headers['Content-Type'] = 'application/json';
        Log::info('performRequestJson', [
            'json' => $jsonData,
            'headers' => $headers,
        ]);
        $response = $client->request($method, $requestUrl, [
            'json' => $jsonData,
            'headers' => $headers,
        ]);
        Log::info('response', [
            $response
        ]);
        return $response->getBody()->getContents();
    }
}
