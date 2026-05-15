<?php

namespace App\Http\Requests\Assessment;

use Illuminate\Foundation\Http\FormRequest;

class ReviewAssessmentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'assessment_id' => 'required|uuid|exists:assessments,id',
            'reviewer_comments' => 'required|string|min:10|max:5000',
            'status' => 'required|string|in:approved,rejected,needs_revision',
            'score' => 'nullable|integer|min:0|max:100',
            'recommendations' => 'nullable|array',
            'recommendations.*' => 'string',
            'review_attachments' => 'nullable|array',
            'review_attachments.*' => 'file|max:10240',
        ];
    }
}