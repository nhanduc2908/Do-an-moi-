<?php

namespace App\Http\Requests\Report;

use Illuminate\Foundation\Http\FormRequest;

class ShareReportRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'report_id' => 'required|uuid|exists:reports,id',
            'recipients' => 'required|array|min:1',
            'recipients.*.email' => 'required|email',
            'recipients.*.role' => 'nullable|string|in:viewer,commenter,editor',
            'permissions' => 'array',
            'permissions.can_download' => 'boolean',
            'permissions.can_print' => 'boolean',
            'permissions.can_forward' => 'boolean',
            'expires_at' => 'nullable|date|after:now',
            'message' => 'nullable|string|max:1000',
            'password_protect' => 'boolean',
            'access_password' => 'required_if:password_protect,true|min:8',
        ];
    }
}