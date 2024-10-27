<?php

namespace App\Http\Requests;

class TransactionCreateRequest extends FormRequest
{
    public function rules()
    {
        return [
            'amount' => 'required|numeric',
            'appId' => 'required|string|max:255',
            'currency' => 'required|string|max:255',
            'merOrderNo' => 'required|string|max:255',
            'notifyUrl' => 'required|string|max:255',
            'returnUrl' => 'required|string|max:255',
        ];
    }
}
