<?php

namespace App\Http\Requests\Encryption;

use Illuminate\Foundation\Http\FormRequest;

class RotateKeyRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'current_key_id' => 'required|uuid|exists:keys,id',
            'new_key_type' => 'nullable|string|in:RSA,AES,ECC',
            'new_key_size' => 'nullable|integer|in:2048,4096,256,384',
            're_encrypt_data' => 'boolean',
            'grace_period_days' => 'nullable|integer|min:0|max:30',
            'reason' => 'nullable|string|max:255',
        ];
    }
}