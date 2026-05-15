@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Security Dashboard')

@section('content')
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-value">{{ $securityScore ?? 78 }}</div>
        <div class="stat-label">Security Score</div>
        <div class="stat-trend {{ $scoreTrend >= 0 ? 'positive' : 'negative' }}">
            {{ $scoreTrend >= 0 ? '↑' : '↓' }} {{ abs($scoreTrend) }}%
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-value">{{ $openIncidents ?? 12 }}</div>
        <div class="stat-label">Open Incidents</div>
        <div class="stat-sub">Critical: {{ $criticalIncidents ?? 3 }}</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-value">{{ $openVulnerabilities ?? 47 }}</div>
        <div class="stat-label">Vulnerabilities</div>
        <div class="stat-sub">Critical: {{ $criticalVulns ?? 8 }}</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-value">{{ $complianceRate ?? 85 }}%</div>
        <div class="stat-label">Compliance Rate</div>
        <div class="stat-sub">ISO 27001: {{ $isoCompliance ?? 82 }}%</div>
    </div>
</div>

<div class="widgets-grid">
    <div class="widget widget-full">
        <div class="widget-title">Security Score Trend</div>
        <canvas id="securityScoreChart" height="300"></canvas>
    </div>
    
    <div class="widget">
        <div class="widget-title">Risk Distribution</div>
        <canvas id="riskChart" height="250"></canvas>
    </div>
    
    <div class="widget">
        <div class="widget-title">Recent Incidents</div>
        <table class="data-table">
            <thead>
                <tr><th>ID</th><th>Title</th><th>Severity</th><th>Status</th></tr>
            </thead>
            <tbody>
                @forelse($recentIncidents as $incident)
                <tr>
                    <td>{{ $incident->incident_code }}</td>
                    <td>{{ $incident->title }}</td>
                    <td><span class="badge badge-{{ strtolower($incident->severity) }}">{{ $incident->severity }}</span></td>
                    <td>{{ $incident->status }}</td>
                </tr>
                @empty
                <tr><td colspan="4">No recent incidents</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="widget">
        <div class="widget-title">System Health</div>
        <div class="health-metrics">
            <div class="metric">API: <span class="status {{ $apiStatus ? 'up' : 'down' }}">{{ $apiStatus ? 'Operational' : 'Down' }}</span></div>
            <div class="metric">Database: <span class="status up">Connected</span></div>
            <div class="metric">Redis: <span class="status up">Connected</span></div>
            <div class="metric">Queue: <span class="status up">Running</span></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    new Chart(document.getElementById('securityScoreChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode($scoreLabels ?? ['Week 1', 'Week 2', 'Week 3', 'Week 4']) !!},
            datasets: [{
                label: 'Security Score',
                data: {!! json_encode($scoreHistory ?? [65, 72, 68, 78]) !!},
                borderColor: '#1a56db',
                backgroundColor: 'rgba(26,86,219,0.1)',
                fill: true
            }]
        }
    });
    
    new Chart(document.getElementById('riskChart'), {
        type: 'doughnut',
        data: {
            labels: ['Critical', 'High', 'Medium', 'Low'],
            datasets: [{
                data: {!! json_encode($riskDistribution ?? [12, 25, 35, 28]) !!},
                backgroundColor: ['#ef4444', '#f59e0b', '#3b82f6', '#10b981']
            }]
        }
    });
</script>
@endpush