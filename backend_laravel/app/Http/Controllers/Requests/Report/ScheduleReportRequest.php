<?php

namespace App\Http\Requests\Report;

use Illuminate\Foundation\Http\FormRequest;

class ScheduleReportRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'report_config' => 'required|array',
            'report_config.type' => 'required|string|in:assessment,compliance,risk',
            'report_config.parameters' => 'array',
            'schedule' => 'required|array',
            'schedule.frequency' => 'required|string|in:daily,weekly,monthly,quarterly',
            'schedule.day_of_week' => 'required_if:schedule.frequency,weekly|integer|min:0|max:6',
            'schedule.day_of_month' => 'required_if:schedule.frequency,monthly|integer|min:1|max:31',
            'schedule.time' => 'required|date_format:H:i',
            'schedule.timezone' => 'required|timezone',
            'recipients' => 'required|array|min:1',
            'recipients.*' => 'email',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'nullable|date|after:start_date',
            'notification_before' => 'nullable|integer|min:0|max:168',
        ];
    }
}