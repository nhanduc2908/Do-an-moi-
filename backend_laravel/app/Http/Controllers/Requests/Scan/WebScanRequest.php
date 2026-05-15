<?php

namespace App\Http\Requests\Scan;

use Illuminate\Foundation\Http\FormRequest;

class WebScanRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'target_url' => 'required|url',
            'scan_depth' => 'nullable|integer|min:1|max:10',
            'scan_pages' => 'nullable|integer|min:1|max:1000',
            'vulnerability_types' => 'nullable|array',
            'vulnerability_types.*' => 'string|in:sql_injection,xss,csrf,ssrf,xxe,rce',
            'crawl_delay' => 'nullable|integer|min:0|max:5',
            'user_agent' => 'nullable|string',
            'auth_cookie' => 'nullable|string',
            'follow_redirects' => 'boolean',
            'timeout_seconds' => 'nullable|integer|min:10|max:300',
        ];
    }
}