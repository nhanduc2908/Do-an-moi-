@extends('admin.layouts.app')

@section('title', 'Incident Response')
@section('page-title', 'Incident Response Center')

@section('content')
<div class="stats-grid">
    <div class="stat-card critical">
        <div class="stat-value">{{ $criticalIncidents ?? 0 }}</div>
        <div class="stat-label">Critical</div>
    </div>
    <div class="stat-card high">
        <div class="stat-value">{{ $highIncidents ?? 0 }}</div>
        <div class="stat-label">High</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $mtResponse ?? 12 }}min</div>
        <div class="stat-label">MT Response</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $mtRecovery ?? 45 }}min</div>
        <div class="stat-label">MT Recovery</div>
    </div>
</div>

<div class="admin-grid">
    <div class="widget">
        <div class="widget-title">Active Incidents</div>
        <table class="data-table">
            <thead><tr><th>ID</th><th>Title</th><th>Severity</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                @foreach($activeIncidents ?? [] as $incident)
                <tr>
                    <td>{{ $incident->incident_code ?? $incident['code'] ?? '-' }}</td>
                    <td>{{ $incident->title ?? $incident['title'] ?? '-' }}</td>
                    <td><span class="badge badge-{{ strtolower($incident->severity ?? '') }}">{{ $incident->severity ?? '-' }}</span></td>
                    <td>{{ $incident->status ?? '-' }}</td>
                    <td><a href="{{ route('admin.incidents.view', $incident->id ?? 0) }}" class="btn btn-sm">Respond</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="widget">
        <div class="widget-title">Playbooks</div>
        <div class="playbook-list">
            <a href="#" class="playbook-item">📘 Ransomware Response</a>
            <a href="#" class="playbook-item">📘 Data Breach Response</a>
            <a href="#" class="playbook-item">📘 DDoS Mitigation</a>
            <a href="#" class="playbook-item">📘 Phishing Incident</a>
        </div>
    </div>
</div>
@endsection