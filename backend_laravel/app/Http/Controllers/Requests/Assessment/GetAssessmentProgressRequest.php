<?php

namespace App\Http\Requests\Assessment;

use Illuminate\Foundation\Http\FormRequest;

class GetAssessmentProgressRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'assessment_id' => 'required|uuid|exists:assessments,id',
            'include_details' => 'sometimes|boolean',
            'sections' => 'nullable|array',
            'sections.*' => 'string',
        ];
    }
}