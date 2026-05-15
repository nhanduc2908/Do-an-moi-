<?php

namespace App\Http\Requests\AI;

use Illuminate\Foundation\Http\FormRequest;

class AIGenerateCriteriaRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'framework' => 'required|string|in:iso27001,nist80053,soc2,hipaa,pci_dss',
            'domain' => 'required|string|max:255',
            'requirements' => 'nullable|string|max:5000',
            'existing_controls' => 'nullable|array',
            'complexity_level' => 'nullable|string|in:basic,standard,advanced',
            'output_format' => 'nullable|string|in:detailed,summary,technical',
            'max_criteria' => 'nullable|integer|min:1|max:50',
            'language' => 'nullable|string|in:en,vi',
        ];
    }
}