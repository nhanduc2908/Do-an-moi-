@extends('admin.layouts.app')

@section('title', 'Security Dashboard')
@section('page-title', 'Security Operations Center')

@section('content')
<div class="stats-grid">
    <div class="stat-card"><div class="stat-value">{{ $openIncidents ?? 0 }}</div><div class="stat-label">Open Incidents</div></div>
    <div class="stat-card"><div class="stat-value">{{ $criticalVulns ?? 0 }}</div><div class="stat-label">Critical Vulns</div></div>
    <div class="stat-card"><div class="stat-value">{{ $securityScore ?? 78 }}%</div><div class="stat-label">Security Score</div></div>
</div>

<div class="action-buttons">
    <a href="{{ route('security-manager.incidents') }}" class="btn btn-danger">🚨 View Incidents</a>
    <a href="{{ route('security-manager.team') }}" class="btn btn-info">👥 Team Management</a>
    <a href="{{ route('security-manager.policies') }}" class="btn btn-secondary">📜 Security Policies</a>
</div>

<div class="widget">
    <div class="widget-title">Recent Incidents</div>
    <table class="data-table">
        <thead><tr><th>ID</th><th>Title</th><th>Severity</th><th>Status</th></tr></thead>
        <tbody>
            @foreach($recentIncidents ?? [] as $incident)
            <tr><td>{{ $incident->incident_code }}</td><td>{{ $incident->title }}</td><td><span class="badge badge-{{ strtolower($incident->severity) }}">{{ $incident->severity }}</span></td><td>{{ $incident->status }}</td></tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection