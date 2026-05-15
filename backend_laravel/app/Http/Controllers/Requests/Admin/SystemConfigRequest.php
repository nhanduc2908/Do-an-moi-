<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SystemConfigRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'config_group' => 'required|string|in:security,assessment,report,integration,notification',
            'configurations' => 'required|array',
            'configurations.*.key' => 'required|string|max:100',
            'configurations.*.value' => 'required',
            'configurations.*.type' => 'nullable|string|in:string,integer,boolean,json,array',
            'configurations.*.description' => 'nullable|string|max:500',
            'apply_immediately' => 'boolean',
            'validate_only' => 'boolean',
        ];
    }
}