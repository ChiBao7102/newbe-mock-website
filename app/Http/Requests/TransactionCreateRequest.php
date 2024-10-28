<?php

namespace App\Http\Requests;

class TransactionCreateRequest extends FormRequest
{
    public function rules()
    {
        return [
            'timestamp' => 'required|string|max:255',
            'order_sn' => 'required|string|max:255',
            'amount' => 'required|string|max:255',
            'notify' => 'required|string|max:255',
            'redirect' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'currency' => 'required',
            'ext_data' => 'required',
        ];
    }
}
