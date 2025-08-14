<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize()
    {
        return true; // no auth needed for login
    }

    public function rules()
    {
        return [
            'email'     => 'required|email',
            'password'  => 'required|string|min:6',
        ];
    }

        // Always force JSON response
    public function wantsJson()
    {
        return true;
    }
}
