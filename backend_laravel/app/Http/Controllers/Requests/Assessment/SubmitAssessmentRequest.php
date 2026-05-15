<?php

namespace App\Http\Requests\Assessment;

use Illuminate\Foundation\Http\FormRequest;

class SubmitAssessmentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'assessment_id' => 'required|uuid|exists:assessments,id',
            'answers' => 'required|array',
            'answers.*.question_id' => 'required|string',
            'answers.*.response' => 'required|string',
            'answers.*.evidence' => 'nullable|array',
            'answers.*.evidence.*' => 'file|mimes:pdf,jpg,png,docx|max:10240',
            'confidence_level' => 'nullable|integer|min:0|max:100',
            'notes' => 'nullable|string|max:2000',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:20480',
        ];
    }
}