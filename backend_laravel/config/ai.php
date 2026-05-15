<?php

return [
    'provider' => env('AI_PROVIDER', 'openai'),
    'openai' => ['api_key' => env('OPENAI_API_KEY'), 'model' => 'gpt-4', 'max_tokens' => 2000, 'temperature' => 0.7],
    'anthropic' => ['api_key' => env('ANTHROPIC_API_KEY'), 'model' => 'claude-3-opus-20240229', 'max_tokens' => 2000],
    'local' => ['api_url' => env('LOCAL_AI_URL', 'http://localhost:8000'), 'model' => 'llama2'],
    'detection_threshold' => 0.75,
    'anomaly_sensitivity' => 'medium',
    'criteria_generation' => ['max_suggestions' => 10, 'confidence_threshold' => 0.7],
];