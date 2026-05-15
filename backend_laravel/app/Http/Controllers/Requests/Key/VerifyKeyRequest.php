<?php

namespace App\Http\Requests\Key;

use Illuminate\Foundation\Http\FormRequest;

class VerifyKeyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'api_key' => 'required|string|exists:api_keys,key',
            'api_secret' => 'required|string',
            'signature' => 'nullable|string',
            'timestamp' => 'nullable|integer',
        ];
    }

    public function messages(): array
    {
        return [
            'api_key.required' => 'API Key là bắt buộc',
            'api_key.exists' => 'API Key không tồn tại',
            'api_secret.required' => 'API Secret là bắt buộc',
        ];
    }
}