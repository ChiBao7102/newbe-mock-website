<?php


namespace App\Services;


use App\Traits\ConsumeExternalService;

class CommonService
{
    use ConsumeExternalService;

    /**
     * The base uri to consume authors service
     * @var string
     */
    public $baseUri;

    public function __construct()
    {
    }


    public function post($data, $url)
    {
        return $this->performRequest('POST', $url, $data);
    }

    public function get($data, $url)
    {
        return $this->performRequest('GET', $url, [], $data);
    }

    public function getAuth($data, $url, $accessToken)
    {
        $headers = [
            'Authorization' => 'Bearer ' . $accessToken
        ];
        return $this->performRequest('GET', $url, [], $data, $headers);
    }

    public function postAuth($data, $url, $accessToken)
    {
        $headers = [
            'Authorization' => 'Bearer ' . $accessToken
        ];
        return $this->performRequest('POST', $url, $data, [], $headers);
    }

    public function postJsonAuth($data, $url, $accessToken)
    {
        $headers = [
            'Authorization' => 'Basic ' . $accessToken
        ];
        return $this->performRequestJson('POST', $url, $data, $headers);
    }

    public function postJson($data, $url)
    {
        return $this->performRequestJson('POST', $url, $data);
    }

    public function getJsonAuth($data, $url, $accessToken)
    {
        $headers = [
            'Authorization' => 'Basic ' . $accessToken
        ];
        return $this->performRequestJson('GET', $url, $data, $headers);
    }


}
