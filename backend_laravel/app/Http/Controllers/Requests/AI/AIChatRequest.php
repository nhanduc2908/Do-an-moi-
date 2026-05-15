<?php

namespace App\Http\Requests\AI;

use Illuminate\Foundation\Http\FormRequest;

class AIChatRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'message' => 'required|string|max:4000',
            'context' => 'nullable|string|in:security,compliance,risk,general',
            'conversation_id' => 'nullable|uuid',
            'temperature' => 'nullable|numeric|min:0|max:1',
            'max_tokens' => 'nullable|integer|min:50|max:2000',
            'include_sources' => 'boolean',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:5120|mimes:txt,pdf,md',
        ];
    }
}