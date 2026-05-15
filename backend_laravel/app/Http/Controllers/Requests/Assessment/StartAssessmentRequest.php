<?php

namespace App\Http\Requests\Assessment;

use Illuminate\Foundation\Http\FormRequest;

class StartAssessmentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'assessment_type' => 'required|string|in:security,compliance,risk,vulnerability',
            'target_system_id' => 'required|uuid|exists:systems,id',
            'scope' => 'required|array',
            'scope.*' => 'string',
            'priority' => 'nullable|string|in:low,medium,high,critical',
            'due_date' => 'nullable|date|after:today',
            'assigned_team' => 'nullable|array',
            'assigned_team.*' => 'uuid|exists:users,id',
            'requirements' => 'nullable|array',
        ];
    }
}