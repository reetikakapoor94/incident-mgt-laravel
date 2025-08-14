<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreIncidentRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Auth handled by middleware
    }

    public function rules()
    {
        return [
            'enterprise_type' => ['required', Rule::in(['Enterprise','Government'])],
            'reporter_name'   => 'required|string|max:255',
            'details'         => 'required|string',
            'reported_at'     => 'nullable|date',
            'priority'        => ['required', Rule::in(['High','Medium','Low'])],
        ];
    }

        // Always force JSON response
    public function wantsJson()
    {
        return true;
    }
}
