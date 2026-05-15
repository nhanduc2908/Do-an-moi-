<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ManageRoleRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|unique:roles,name|max:50|regex:/^[a-zA-Z_]+$/',
            'display_name' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'permissions' => 'required|array|min:1',
            'permissions.*' => 'string|exists:permissions,name',
            'guard_name' => 'nullable|string|in:web,api',
            'is_system_role' => 'boolean',
            'level' => 'nullable|integer|min:1|max:100',
        ];
    }
}