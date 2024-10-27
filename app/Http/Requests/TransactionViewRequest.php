<?php


namespace App\Http\Requests;


class TransactionViewRequest extends FormRequest
{
    public function rules()
    {
        return [
            'txID' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'domain' => 'required|string|max:255',
            'skey' => 'required|string|max:255',
            'type' => 'required|numeric',
        ];
    }
}
