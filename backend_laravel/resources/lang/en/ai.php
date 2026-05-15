<?php

return [
    'detection' => [
        'threat_detected' => 'AI threat detection alert',
        'anomaly_detected' => 'Anomaly detected in network traffic',
        'confidence' => 'Detection confidence: :percentage%',
        'false_positive' => 'Mark as false positive',
    ],
    'chatbot' => [
        'welcome' => 'Hello! I am your security assistant. How can I help you today?',
        'help' => 'I can help with vulnerability scanning, compliance checks, incident response, and security reports.',
        'scan_request' => 'I will initiate a vulnerability scan for your system.',
        'report_request' => 'Generating security report. Please specify the timeframe.',
        'incident_request' => 'I have logged this incident and notified the security team.',
    ],
    'criteria' => [
        'generated' => 'AI-generated criteria suggestions',
        'confidence' => 'AI confidence score: :score%',
        'apply' => 'Apply suggestion',
        'regenerate' => 'Regenerate suggestions',
        'customize' => 'Customize criteria',
    ],
    'prediction' => [
        'risk_forecast' => 'Risk prediction for next :days days',
        'vulnerability_trend' => 'Vulnerability trend prediction',
        'incident_forecast' => 'Incident volume forecast',
    ],
];