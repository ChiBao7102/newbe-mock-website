<?php

namespace App\Http\Requests;

class RemittanceCreateRequest extends FormRequest
{
    public function rules()
    {
        return [
            'amount' => 'required|numeric',
            'appId' => 'required|string|max:255',
            'currency' => 'required|string|max:255',
            'extra.accountName' => 'required|string|max:255',
            'extra.accountNo' => 'required|string|max:255',
            'extra.bankCode' => 'required|in:CPF,EMAIL,PHONE,EVP',
            'merOrderNo' => 'required|string|max:255',
            'notifyUrl' => 'required|string|max:255'
        ];
    }
}
