<?php


namespace App\Http\Requests;


class TransactionCallackRequest extends FormRequest
{
    public function rules()
    {
        return [
            'tranID' => 'required|string|max:255',
            'orderid' => 'required|string|max:255',
            'status' => 'required|numeric',
            'domain' => 'required|in:PHP',
            'amount' => 'string|max:500',
            'currency' => 'nullable|string|email|max:255',
            'appcode' => 'nullable|string|max:255',
            'paydate' => 'string|required|max:1026',
            'skey' => 'string|required|max:1026',
        ];
    }
}
