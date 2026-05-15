<?php

namespace App\Http\Requests\Report;

use Illuminate\Foundation\Http\FormRequest;

class GenerateReportRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'report_type' => 'required|string|in:assessment,compliance,risk_summary,executive,technical',
            'filters' => 'required|array',
            'filters.date_from' => 'nullable|date',
            'filters.date_to' => 'nullable|date|after_or_equal:filters.date_from',
            'filters.status' => 'nullable|array',
            'filters.department' => 'nullable|string',
            'include_sections' => 'required|array',
            'include_sections.*' => 'string|in:executive_summary,methodology,findings,recommendations,appendices',
            'language' => 'nullable|string|in:en,vi,ja,ko',
            'template_id' => 'nullable|string|exists:templates,id',
        ];
    }
}