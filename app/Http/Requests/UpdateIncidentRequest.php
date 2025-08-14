<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateIncidentRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Auth handled by middleware + policy
    }

    public function rules()
    {
        return [
            'enterprise_type' => [Rule::in(['Enterprise','Government'])],
            'reporter_name'   => 'string|max:255',
            'details'         => 'string',
            'reported_at'     => 'nullable|date',
            'priority'        => [Rule::in(['High','Medium','Low'])],
            'status'          => [Rule::in(['Open','In progress','Closed'])],
        ];
    }

        // Always force JSON response
    public function wantsJson()
    {
        return true;
    }
}
