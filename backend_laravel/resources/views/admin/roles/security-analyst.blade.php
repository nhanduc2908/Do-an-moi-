@extends('admin.layouts.app')

@section('title', 'Security Analyst Dashboard')
@section('page-title', 'Threat Analysis & Monitoring')

@section('content')
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-value">{{ $todayThreats ?? 0 }}</div>
        <div class="stat-label">Threats Today</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $falsePositives ?? 0 }}</div>
        <div class="stat-label">False Positives</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $avgResponseTime ?? 15 }}min</div>
        <div class="stat-label">Avg Response Time</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $mitigationRate ?? 94 }}%</div>
        <div class="stat-label">Mitigation Rate</div>
    </div>
</div>

<div class="admin-grid">
    <div class="widget">
        <div class="widget-title">Threat Timeline</div>
        <canvas id="threatTimeline" height="250"></canvas>
    </div>
    
    <div class="widget">
        <div class="widget-title">Alerts Queue</div>
        <table class="data-table">
            <thead><tr><th>Time</th><th>Alert</th><th>Severity</th><th>Action</th></tr></thead>
            <tbody>
                @foreach($alerts ?? [] as $alert)
                <tr>
                    <td>{{ $alert['time'] ?? '-' }}</td>
                    <td>{{ $alert['message'] ?? '-' }}</td>
                    <td><span class="badge badge-{{ $alert['severity'] ?? 'info' }}">{{ $alert['severity'] ?? '-' }}</span></td>
                    <td><button class="btn btn-sm">Investigate</button></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection