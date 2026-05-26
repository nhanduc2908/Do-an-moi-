@extends('admin.layouts.app')

@section('title', 'Security Manager Dashboard')
@section('page-title', 'Security Operations Center')

@section('content')
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-value">{{ $openIncidents ?? 0 }}</div>
        <div class="stat-label">Open Incidents</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $criticalVulns ?? 0 }}</div>
        <div class="stat-label">Critical Vulnerabilities</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $activeAssessments ?? 0 }}</div>
        <div class="stat-label">Active Assessments</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $securityScore ?? 78 }}%</div>
        <div class="stat-label">Security Score</div>
    </div>
</div>

<div class="admin-grid">
    <div class="widget">
        <div class="widget-title">Security Overview</div>
        <canvas id="securityTrend" height="250"></canvas>
    </div>
    
    <div class="widget">
        <div class="widget-title">Recent Incidents</div>
        <table class="data-table">
            <thead><tr><th>ID</th><th>Title</th><th>Severity</th><th>Status</th></tr></thead>
            <tbody>
                @forelse($recentIncidents ?? [] as $incident)
                <tr>
                    <td>{{ $incident->incident_code ?? $incident['code'] ?? '-' }}</td>
                    <td>{{ $incident->title ?? $incident['title'] ?? '-' }}</td>
                    <td><span class="badge badge-{{ strtolower($incident->severity ?? '') }}">{{ $incident->severity ?? '-' }}</span></td>
                    <td>{{ $incident->status ?? '-' }}</td>
                </tr>
                @empty
                <tr><td colspan="4">No recent incidents</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="widget">
        <div class="widget-title">Quick Actions</div>
        <div class="action-buttons">
            <a href="{{ route('admin.assessments.create') }}" class="btn btn-primary">📋 New Assessment</a>
            <a href="{{ route('admin.incidents.create') }}" class="btn btn-danger">🚨 Report Incident</a>
            <a href="{{ route('admin.vulnerabilities.scan') }}" class="btn btn-warning">🔍 Run Scan</a>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    new Chart(document.getElementById('securityTrend'), {
        type: 'line',
        data: {
            labels: {!! json_encode($trendLabels ?? ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun']) !!},
            datasets: [{
                label: 'Security Score',
                data: {!! json_encode($securityTrends ?? [65, 70, 68, 72, 75, 78]) !!},
                borderColor: '#e67e22',
                fill: true
            }]
        }
    });
</script>
@endpush
@endsection