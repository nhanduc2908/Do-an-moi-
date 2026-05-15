<?php

namespace App\Services\Module27_ReportAnalytics;

use App\Models\Module27_ReportAnalytics\SecurityScore;

class SecurityScoreService
{
    public function calculateScore($organizationId)
    {
        $scores = [];
        
        // Technical Security Score (30%)
        $scores['technical'] = $this->calculateTechnicalScore($organizationId);
        
        // Compliance Score (25%)
        $scores['compliance'] = $this->calculateComplianceScore($organizationId);
        
        // Incident Response Score (20%)
        $scores['incident_response'] = $this->calculateIncidentResponseScore($organizationId);
        
        // Training Awareness Score (15%)
        $scores['training'] = $this->calculateTrainingScore($organizationId);
        
        // Physical Security Score (10%)
        $scores['physical'] = $this->calculatePhysicalSecurityScore($organizationId);
        
        $totalScore = 0;
        $weights = [
            'technical' => 0.30,
            'compliance' => 0.25,
            'incident_response' => 0.20,
            'training' => 0.15,
            'physical' => 0.10
        ];
        
        foreach ($scores as $category => $score) {
            $totalScore += $score * $weights[$category];
        }
        
        $securityScore = SecurityScore::create([
            'organization_id' => $organizationId,
            'overall_score' => round($totalScore, 2),
            'category_scores' => $scores,
            'calculated_at' => now(),
            'valid_until' => now()->addDays(7)
        ]);
        
        return $securityScore;
    }

    protected function calculateTechnicalScore($organizationId)
    {
        $score = 0;
        // Calculate based on patch status, vulnerabilities, etc.
        return 85;
    }

    protected function calculateComplianceScore($organizationId)
    {
        $score = 0;
        // Calculate based on compliance checks
        return 78;
    }

    protected function calculateIncidentResponseScore($organizationId)
    {
        $score = 0;
        // Calculate based on incident metrics
        return 82;
    }

    protected function calculateTrainingScore($organizationId)
    {
        $score = 0;
        // Calculate based on training completion
        return 75;
    }

    protected function calculatePhysicalSecurityScore($organizationId)
    {
        $score = 0;
        // Calculate based on physical security
        return 90;
    }

    public function getScoreHistory($organizationId, $days = 90)
    {
        return SecurityScore::where('organization_id', $organizationId)
            ->where('calculated_at', '>=', now()->subDays($days))
            ->orderBy('calculated_at', 'asc')
            ->get();
    }

    public function compareWithBenchmark($organizationId)
    {
        $currentScore = SecurityScore::where('organization_id', $organizationId)
            ->latest()
            ->first();
        
        $industryAverage = [
            'overall' => 72,
            'technical' => 70,
            'compliance' => 68,
            'incident_response' => 65,
            'training' => 60,
            'physical' => 75
        ];
        
        return [
            'your_score' => $currentScore->overall_score,
            'industry_average' => $industryAverage['overall'],
            'difference' => $currentScore->overall_score - $industryAverage['overall'],
            'percentile' => $this->calculatePercentile($currentScore->overall_score),
            'category_comparison' => array_map(function($category, $yourScore) use ($industryAverage) {
                return [
                    'your_score' => $yourScore,
                    'industry_average' => $industryAverage[$category],
                    'difference' => $yourScore - $industryAverage[$category]
                ];
            }, array_keys($currentScore->category_scores), $currentScore->category_scores)
        ];
    }

    protected function calculatePercentile($score)
    {
        // Simplified percentile calculation
        if ($score >= 90) return 95;
        if ($score >= 80) return 80;
        if ($score >= 70) return 60;
        if ($score >= 60) return 40;
        if ($score >= 50) return 20;
        return 10;
    }
}