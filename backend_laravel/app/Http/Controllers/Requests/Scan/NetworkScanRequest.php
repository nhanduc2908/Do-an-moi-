<?php

namespace App\Http\Requests\Scan;

use Illuminate\Foundation\Http\FormRequest;

class NetworkScanRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'target' => 'required|string',
            'scan_type' => 'required|string|in:quick,full,custom',
            'ports' => 'nullable|array',
            'ports.*' => 'integer|min:1|max:65535',
            'port_range' => 'nullable|string|regex:/^\d+-\d+$/',
            'timeout' => 'nullable|integer|min:1|max:60',
            'scan_method' => 'nullable|string|in:syn,connect,fin,null,ack',
            'discover_os' => 'boolean',
            'discover_services' => 'boolean',
            'aggressive_scan' => 'boolean',
            'exclude_hosts' => 'nullable|array',
        ];
    }
}