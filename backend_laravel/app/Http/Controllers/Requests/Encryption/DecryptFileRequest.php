<?php

namespace App\Http\Requests\Encryption;

use Illuminate\Foundation\Http\FormRequest;

class DecryptFileRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'encrypted_file' => 'required|file|max:51200',
            'key_id' => 'nullable|uuid|exists:keys,id',
            'passphrase' => 'required_without:key_id|string|min:8',
            'verify_integrity' => 'boolean',
            'output_path' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'passphrase.required_without' => 'Either key_id or passphrase is required',
        ];
    }
}