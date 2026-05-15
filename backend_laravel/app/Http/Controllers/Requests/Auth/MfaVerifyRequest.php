<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class MfaVerifyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'size:6'],
            'method' => ['nullable', 'string', 'in:totp,sms,email'],
            'recovery_code' => ['nullable', 'string', 'size:8'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'code.required' => 'MFA code is required',
            'code.size' => 'MFA code must be 6 digits',
            'recovery_code.size' => 'Recovery code must be 8 characters',
        ];
    }
}