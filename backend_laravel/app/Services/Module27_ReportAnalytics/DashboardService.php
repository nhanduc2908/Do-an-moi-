<?php

namespace App\Services\Module27_ReportAnalytics;

use App\Models\Module27_ReportAnalytics\Dashboard;
use App\Models\Module27_ReportAnalytics\DashboardWidget;

class DashboardService
{
    public function createDashboard($data)
    {
        $dashboard = Dashboard::create([
            'dashboard_name' => $data['name'],
            'layout' => $data['layout'] ?? ['type' => 'grid', 'columns' => 3],
            'user_id' => auth()->id(),
            'is_default' => $data['is_default'] ?? false
        ]);
        
        if ($dashboard->is_default) {
            Dashboard::where('user_id', auth()->id())
                ->where('id', '!=', $dashboard->id)
                ->update(['is_default' => false]);
        }
        
        return $dashboard;
    }

    public function addWidget($dashboardId, $widgetData)
    {
        $widget = DashboardWidget::create([
            'widget_name' => $widgetData['name'],
            'widget_type' => $widgetData['type'],
            'configuration' => $widgetData['config'],
            'position' => $widgetData['position'],
            'size' => $widgetData['size'],
            'refresh_interval' => $widgetData['refresh_interval'] ?? 300,
            'is_enabled' => true
        ]);
        
        $dashboard = Dashboard::findOrFail($dashboardId);
        $widgets = $dashboard->widgets;
        $widgets[] = $widget->id;
        $dashboard->widgets = $widgets;
        $dashboard->save();
        
        return $widget;
    }

    public function getDashboardData($dashboardId)
    {
        $dashboard = Dashboard::with('widgets')->findOrFail($dashboardId);
        
        $data = [
            'dashboard' => $dashboard,
            'widgets_data' => []
        ];
        
        foreach ($dashboard->widgets as $widget) {
            $data['widgets_data'][$widget->id] = $this->getWidgetData($widget);
        }
        
        return $data;
    }

    protected function getWidgetData($widget)
    {
        switch ($widget->widget_type) {
            case 'security_score':
                return $this->getSecurityScoreData();
            case 'vulnerability_trend':
                return $this->getVulnerabilityTrendData();
            case 'incident_summary':
                return $this->getIncidentSummaryData();
            case 'compliance_status':
                return $this->getComplianceStatusData();
            case 'recent_activities':
                return $this->getRecentActivities();
            default:
                return [];
        }
    }

    protected function getSecurityScoreData()
    {
        $scoreService = new SecurityScoreService();
        $score = $scoreService->calculateScore(auth()->user()->organization_id);
        
        return [
            'overall_score' => $score->overall_score,
            'category_scores' => $score->category_scores,
            'trend' => $this->getScoreTrend()
        ];
    }

    protected function getVulnerabilityTrendData()
    {
        return [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            'critical' => [5, 3, 2, 4, 1, 0],
            'high' => [12, 10, 8, 6, 5, 4],
            'medium' => [20, 18, 15, 12, 10, 8],
            'low' => [30, 28, 25, 22, 20, 18]
        ];
    }

    protected function getIncidentSummaryData()
    {
        return [
            'open' => 3,
            'investigating' => 2,
            'resolved' => 15,
            'total' => 20,
            'by_severity' => [
                'critical' => 1,
                'high' => 2,
                'medium' => 5,
                'low' => 12
            ]
        ];
    }

    protected function getComplianceStatusData()
    {
        return [
            'iso27001' => 85,
            'gdpr' => 90,
            'pci_dss' => 75,
            'hipaa' => 95
        ];
    }

    protected function getRecentActivities()
    {
        return [
            ['action' => 'User logged in', 'user' => 'john@example.com', 'time' => '5 minutes ago'],
            ['action' => 'Vulnerability scan completed', 'user' => 'system', 'time' => '10 minutes ago'],
            ['action' => 'Report generated', 'user' => 'admin', 'time' => '1 hour ago'],
            ['action' => 'Incident resolved', 'user' => 'security_team', 'time' => '2 hours ago'],
            ['action' => 'Policy updated', 'user' => 'compliance', 'time' => '3 hours ago']
        ];
    }

    protected function getScoreTrend()
    {
        return [
            'current' => 82,
            'previous' => 78,
            'change' => '+4'
        ];
    }
}