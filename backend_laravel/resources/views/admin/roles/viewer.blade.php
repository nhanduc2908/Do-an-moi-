@extends('admin.layouts.app')

@section('title', 'Viewer Dashboard')
@section('page-title', 'Security Dashboard (View Only)')

@section('content')
<div class="alert alert-info">
    <span>ℹ️ You have view-only access. Contact your administrator for edit permissions.</span>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-value">{{ $securityScore ?? 78 }}</div>
        <div class="stat-label">Security Score</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $openIncidents ?? 12 }}</div>
        <div class="stat-label">Open Incidents</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $totalVulnerabilities ?? 47 }}</div>
        <div class="stat-label">Vulnerabilities</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $complianceRate ?? 85 }}%</div>
        <div class="stat-label">Compliance Rate</div>
    </div>
</div>

<div class="admin-grid">
    <div class="widget">
        <div class="widget-title">Security Overview</div>
        <canvas id="securityOverview" height="250"></canvas>
    </div>
    
    <div class="widget">
        <div class="widget-title">Recent Reports</div>
        <table class="data-table">
            <thead><tr><th>Report Name</th><th>Date</th><th>Actions</th></tr></thead>
            <tbody>
                @foreach($recentReports ?? [] as $report)
                <tr>
                    <td>{{ $report['name'] ?? '-' }}</td>
                    <td>{{ $report['date'] ?? '-' }}</td>
                    <td><a href="{{ route('admin.reports.view', $report['id'] ?? 0) }}" class="btn btn-sm">View</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection