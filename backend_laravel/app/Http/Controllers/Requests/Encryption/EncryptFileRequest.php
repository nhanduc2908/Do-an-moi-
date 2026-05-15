<?php

namespace App\Http\Requests\Encryption;

use Illuminate\Foundation\Http\FormRequest;

class EncryptFileRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'file' => 'required|file|max:51200',
            'algorithm' => 'required|string|in:AES-256-GCM,ChaCha20-Poly1305',
            'key_id' => 'nullable|uuid|exists:keys,id',
            'compress' => 'boolean',
            'metadata' => 'nullable|array',
            'expires_in_days' => 'nullable|integer|min:1|max:365',
        ];
    }
}