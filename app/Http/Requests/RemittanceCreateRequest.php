<?php

namespace App\Http\Requests;

class RemittanceCreateRequest extends FormRequest
{
    public function rules()
    {
        return [
            'timestamp' => 'required',
            'order_sn' => 'required|string|max:255',
            'amount' => 'required|string|max:255',
            'notify' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'country' => 'required',
            'currency' => 'required|string|max:255',
            'payload' => 'required'
        ];
    }
}
