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
            $attributes['amount'] = (int)$attributes['amount'];
            $attributes['appId'] = env('APP_ID');
            $attributes['key'] = env('APP_KEY');
            // Input data (could be any system signature you need to encrypt)
            $signature = implode('&', array_map(
                function ($key, $value) {
                    return $key . '=' . $value;
                },
                array_keys($attributes),
                $attributes
            ));
            Log::info('signature', [$signature]);
            $encryptedSignature = hash('sha256', $signature);
            unset($attributes['key']);
            $attributes['sign'] = $encryptedSignature;
            Log::debug('attributes', [$attributes]);
            Log::info('url', [env('CREATE_TRANSACTION_URL')]);
            $data = $this->commonService->postJson($attributes, env('CREATE_TRANSACTION_URL'));
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
            $attributes['amount'] = (int)$attributes['amount'];
            $attributes['appId'] = env('APP_ID');
            $attributes['key'] = env('APP_KEY');
            $queryString = "amount={$attributes['amount']}&appId={$attributes['appId']}&currency={$attributes['currency']}";

            // Manually append the nested 'extra' fields
            $extraString = "accountName={$attributes['extra']['accountName']}&accountNo={$attributes['extra']['accountNo']}&bankCode={$attributes['extra']['bankCode']}";
            $queryString .= "&extra={$extraString}";

            // Append the remaining fields
            $queryString .= "&merOrderNo={$attributes['merOrderNo']}&notifyUrl={$attributes['notifyUrl']}&key={$attributes['key']}";
            $encryptedSignature = hash('sha256', $queryString);
            unset($attributes['key']);
            $attributes['sign'] = $encryptedSignature;
            $data = $this->commonService->postJson($attributes, env('CREATE_REMITTANCE_URL'));
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

}
