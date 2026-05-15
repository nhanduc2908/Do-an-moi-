<?php

namespace App\Http\Requests\AI;

use Illuminate\Foundation\Http\FormRequest;

class AIPredictRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'prediction_type' => 'required|string|in:risk_score,threat_level,vulnerability_trend,compliance_status',
            'data' => 'required|array',
            'data.historical' => 'nullable|array',
            'data.current_metrics' => 'nullable|array',
            'timeframe_days' => 'nullable|integer|min:7|max:365',
            'confidence_interval' => 'nullable|numeric|min:0.8|max:0.99',
            'model_version' => 'nullable|string',
            'include_features' => 'boolean',
        ];
    }
}