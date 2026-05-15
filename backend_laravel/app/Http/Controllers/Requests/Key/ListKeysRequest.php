<?php

namespace App\Http\Requests\Key;

use Illuminate\Foundation\Http\FormRequest;

class ListKeysRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'status' => 'nullable|string|in:active,revoked,expired,all',
            'type' => 'nullable|string|in:RSA,AES,ECC',
            'purpose' => 'nullable|string|in:encryption,authentication,signing',
            'per_page' => 'nullable|integer|min:1|max:100',
            'page' => 'nullable|integer|min:1',
            'sort_by' => 'nullable|string|in:created_at,expires_at,type',
            'sort_order' => 'nullable|string|in:asc,desc',
            'search' => 'nullable|string|max:255',
        ];
    }
}