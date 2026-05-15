<?php

namespace App\Http\Requests\Report;

use Illuminate\Foundation\Http\FormRequest;

class ExportReportRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'report_id' => 'required|uuid|exists:reports,id',
            'export_format' => 'required|string|in:pdf,docx,html,markdown',
            'compression' => 'boolean',
            'encrypt' => 'boolean',
            'encryption_password' => 'required_if:encrypt,true|min:8|confirmed',
            'page_orientation' => 'nullable|string|in:portrait,landscape',
            'include_raw_data' => 'boolean',
            'chart_format' => 'nullable|string|in:svg,png,embedded',
        ];
    }
}