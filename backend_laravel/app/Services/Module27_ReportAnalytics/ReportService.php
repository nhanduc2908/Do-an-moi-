<?php

namespace App\Services\Module27_ReportAnalytics;

use App\Models\Module27_ReportAnalytics\Report;
use App\Models\Module27_ReportAnalytics\ReportSchedule;

class ReportService
{
    public function generateReport($data)
    {
        $report = Report::create([
            'report_name' => $data['name'],
            'report_type' => $data['type'],
            'filters' => $data['filters'] ?? [],
            'format' => $data['format'] ?? 'pdf',
            'generated_by' => auth()->id(),
            'generated_at' => now()
        ]);
        
        $content = $this->buildReportContent($report);
        $filePath = $this->saveReportFile($report, $content);
        
        $report->file_path = $filePath;
        $report->file_size = filesize($filePath);
        $report->save();
        
        return $report;
    }

    protected function buildReportContent($report)
    {
        switch ($report->report_type) {
            case 'security_summary':
                return $this->buildSecuritySummary($report->filters);
            case 'vulnerability_report':
                return $this->buildVulnerabilityReport($report->filters);
            case 'compliance_report':
                return $this->buildComplianceReport($report->filters);
            case 'incident_report':
                return $this->buildIncidentReport($report->filters);
            default:
                return ['error' => 'Unknown report type'];
        }
    }

    protected function buildSecuritySummary($filters)
    {
        $scoreService = new SecurityScoreService();
        $score = $scoreService->calculateScore(auth()->user()->organization_id);
        
        return [
            'title' => 'Security Summary Report',
            'generated_at' => now()->toDateTimeString(),
            'overall_score' => $score->overall_score,
            'category_scores' => $score->category_scores,
            'risk_level' => $this->getRiskLevel($score->overall_score),
            'recommendations' => $this->getRecommendations($score)
        ];
    }

    protected function buildVulnerabilityReport($filters)
    {
        return [
            'title' => 'Vulnerability Report',
            'generated_at' => now()->toDateTimeString(),
            'total_vulnerabilities' => 0,
            'by_severity' => [],
            'top_vulnerabilities' => [],
            'remediation_status' => []
        ];
    }

    protected function buildComplianceReport($filters)
    {
        return [
            'title' => 'Compliance Report',
            'generated_at' => now()->toDateTimeString(),
            'standards' => [],
            'compliance_status' => [],
            'gaps' => [],
            'remediation_plan' => []
        ];
    }

    protected function buildIncidentReport($filters)
    {
        return [
            'title' => 'Incident Report',
            'generated_at' => now()->toDateTimeString(),
            'total_incidents' => 0,
            'by_status' => [],
            'by_severity' => [],
            'response_times' => []
        ];
    }

    protected function saveReportFile($report, $content)
    {
        $path = storage_path("reports/{$report->id}_" . date('Ymd_His') . ".{$report->format}");
        
        if ($report->format === 'json') {
            file_put_contents($path, json_encode($content, JSON_PRETTY_PRINT));
        } else {
            // Convert to PDF using PDF generator
            file_put_contents($path, json_encode($content));
        }
        
        return $path;
    }

    protected function getRiskLevel($score)
    {
        if ($score >= 80) return 'Low';
        if ($score >= 60) return 'Medium';
        if ($score >= 40) return 'High';
        return 'Critical';
    }

    protected function getRecommendations($score)
    {
        $recommendations = [];
        
        foreach ($score->category_scores as $category => $categoryScore) {
            if ($categoryScore < 70) {
                $recommendations[] = "Improve {$category} security controls";
            }
        }
        
        return $recommendations;
    }

    public function scheduleReport($data)
    {
        $schedule = ReportSchedule::create([
            'report_id' => $data['report_id'],
            'frequency' => $data['frequency'],
            'time' => $data['time'],
            'day_of_week' => $data['day_of_week'] ?? null,
            'day_of_month' => $data['day_of_month'] ?? null,
            'recipients' => $data['recipients'],
            'is_active' => true
        ]);
        
        return $schedule;
    }

    public function getReportHistory($reportType = null)
    {
        $query = Report::where('generated_by', auth()->id());
        
        if ($reportType) {
            $query->where('report_type', $reportType);
        }
        
        return $query->orderBy('generated_at', 'desc')->get();
    }
}