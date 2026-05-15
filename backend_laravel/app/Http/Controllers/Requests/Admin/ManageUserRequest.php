<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ManageUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $method = $this->method();
        
        if ($method === 'POST') {
            return [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8|confirmed',
                'role' => 'required|string|exists:roles,name',
                'department' => 'nullable|string|max:100',
                'permissions' => 'nullable|array',
            ];
        }
        
        if ($method === 'PUT' || $method === 'PATCH') {
            return [
                'user_id' => 'required|uuid|exists:users,id',
                'name' => 'sometimes|string|max:255',
                'email' => 'sometimes|email|unique:users,email,' . $this->user_id,
                'role' => 'sometimes|string|exists:roles,name',
                'status' => 'sometimes|string|in:active,inactive,suspended',
                'department' => 'nullable|string|max:100',
            ];
        }
        
        return [];
    }
}