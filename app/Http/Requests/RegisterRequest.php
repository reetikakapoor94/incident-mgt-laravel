<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true; // no auth needed for register
    }

    public function rules()
    {
        return [
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:users',
            'password'  => 'required|string|min:6|confirmed',
            'phone'     => 'nullable|string|max:20',
            'address'   => 'nullable|string|max:255',
            'pincode'   => 'nullable|string|max:10',
            'city'      => 'nullable|string|max:100',
            'country'   => 'nullable|string|max:100',
        ];
    }

       // Always force JSON response
    public function wantsJson()
    {
        return true;
    }
}
