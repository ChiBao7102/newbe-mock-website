<?php

namespace App\Http\Controllers;

use App\Http\Requests\RemittanceCreateRequest;
use App\Http\Requests\TransactionCreateRequest;
use App\Http\Requests\TransactionViewRequest;
use App\Services\CommonService;
use Faker\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class MerchantController extends Controller
{
    protected $commonService;

    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }

    public function createTransaction(TransactionCreateRequest $request)
    {
        $attributes = $request->only(array_keys($request->rules()));
        try {
            Log::info('createTransaction', $attributes);
            // Input data (could be any system signature you need to encrypt)
            $dataRequest = [
                'app_id' => env('APP_ID'),
                'params'=> $attributes
            ];
            $key = env('APP_SECRET');
            $sign = $this->get_md5($dataRequest['params'],$key);
            $dataRequest['sign'] = $sign;
            Log::debug('attributes', [$dataRequest]);
            Log::info('url', [env('CREATE_TRANSACTION_URL')]);
            $data = $this->commonService->postJson($dataRequest, env('CREATE_TRANSACTION_URL'));
            $dataJson = json_decode($data);
            return $this->successResponse($dataJson);
        } catch (\Exception $e) {
            Log::error('createTransactionError', [$e->getMessage()]);
            return $this->errorMessage($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function createRemittance(RemittanceCreateRequest $request)
    {
        $attributes = $request->only(array_keys($request->rules()));
        try {
            Log::info('createRemittance', $attributes);
            $dataRequest = [
                'app_id' => env('APP_ID'),
                'params'=> $attributes
            ];
            $key = env('APP_SECRET');
            $sign = $this->get_md5($dataRequest['params'],$key);
            $dataRequest['sign'] = $sign;
            $data = $this->commonService->postJson($dataRequest, env('CREATE_REMITTANCE_URL'));
            $dataJson = json_decode($data);
            return $this->successResponse($dataJson);
        } catch (\Exception $e) {
            Log::error('createRemittanceError', [$e->getMessage()]);
            return $this->errorMessage($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function callback(Request $request)
    {
        $attributes = $request->all();
        Log::info('callback-payin', $attributes);
        $data = $this->commonService->post($attributes, env('CALLBACK_TRANSACTION_FPAY'));
        Log::info('callbackResponse', [$data]);
        return $this->successResponse($attributes);
    }

    public function callbackSuccess(Request $request)
    {
        $attributes = $request->all();
        Log::info('callback-test-payin-success', $attributes);
        $data = $this->commonService->post($attributes, env('CALLBACK_TRANSACTION_FPAY'));
        Log::info('callbackResponse', [$data]);
        return $this->successResponse($attributes);
    }

    public function callbackFail(Request $request)
    {
        $attributes = $request->all();
        Log::info('callback-test-payin-fail', $attributes);
        $data = $this->commonService->post($attributes, env('CALLBACK_TRANSACTION_FPAY'));
        Log::info('callbackResponse', [$data]);
        return $this->successResponse($attributes);
    }

    public function callbackPayout(Request $request)
    {
        $attributes = $request->all();
        Log::info('callback-payout', $attributes);
        $data = $this->commonService->post($attributes, env('CALLBACK_REMITTANCE_FPAY'));
        Log::info('callbackResponse', [$data]);
        return $this->successResponse($attributes);
    }

    public function retrieve(Request $request)
    {
        $attributes = $request->transactionId;
        try {
            Log::info('Retrieve payin transaction_id:', [$attributes]);
            $requestData = [
                'appId' => env('APP_ID'),
                'orderNo' => $attributes,
                'key' =>  env('APP_KEY')
            ];
            Log::info('Retrieve payin transaction_id:', [$requestData]);
            $signature = implode('&', array_map(
                function ($key, $value) {
                    return $key . '=' . $value;
                },
                array_keys($requestData),
                $requestData
            ));
            Log::info('signature', [$signature]);
            $encryptedSignature = hash('sha256', $signature);
            unset($requestData['key']);
            $requestData['sign'] = $encryptedSignature;
            $data = $this->commonService->get($requestData, env('GET_RETRIEVE_URL'));
            $dataJson = json_decode($data);
            return $this->successResponse($dataJson);
        } catch (\Exception $e) {
            Log::error('Retrieve Error:', [$e->getMessage()]);
            return $this->errorMessage($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function gettype(Request $request){
        $attributes = [
            'app_id' => env('APP_ID'),
            'params'=>[
                'timestamp' => time(),
                'currency' => $request->currency,
            ]
        ];
        $key = env('APP_SECRET');
        $sign = $this->get_md5($attributes['params'],$key);
        $attributes['sign'] = $sign;
        $data = $this->commonService->postJson($attributes, 'https://openapi.hashop.link/api/v1/expend.types');
        Log::info('callbackResponse', [$data]);
        return $this->successResponse(json_decode($data));
    }

    public function get_md5($body,$key){
        ksort($body);
        reset($body);
        $sign_str = http_build_query($body);
        $sign_str .= '&key=' . $key;
        return md5($sign_str);
    }


}
