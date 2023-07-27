<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SignupRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            //
            'name' => 'min:3|max:10|alpha',
            'email' => 'email|unique:users',
            'password' => 'min:5'
        ];
    }
}
