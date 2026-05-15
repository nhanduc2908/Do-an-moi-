<?php

namespace App\Http\Requests\Key;

use Illuminate\Foundation\Http\FormRequest;

class RevokeKeyRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'key_id' => 'required|uuid|exists:keys,id',
            'reason' => 'required|string|in:compromised,expired,superseded,manual',
            'description' => 'nullable|string|max:500',
            'force' => 'sometimes|boolean',
        ];
    }
}