<?php

namespace App\Http\Requests\Key;

use Illuminate\Foundation\Http\FormRequest;

class GenerateKeyRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'type' => 'required|string|in:RSA,AES,ECC',
            'size' => 'required|integer|in:2048,4096,256,384',
            'purpose' => 'required|string|in:encryption,authentication,signing',
            'expiry_days' => 'nullable|integer|min:1|max:3650',
            'metadata' => 'nullable|array',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
        ];
    }
}