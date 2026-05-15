<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class TwoFactorRequest extends FormRequest
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
            'method' => ['required', 'string', 'in:totp,sms,email'],
            'phone_number' => ['required_if:method,sms', 'nullable', 'string'],
            'email' => ['required_if:method,email', 'nullable', 'email'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'method.required' => 'Two-factor method is required',
            'phone_number.required_if' => 'Phone number is required for SMS method',
            'email.required_if' => 'Email is required for email method',
        ];
    }
}