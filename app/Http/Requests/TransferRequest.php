<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransferRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            //
            'receiver_email' => 'required|email|exists:users,email',
            'amount' => 'required|numeric|min:0.01'
        ];
    }
}
