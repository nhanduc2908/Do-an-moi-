@extends('admin.layouts.app')

@section('title', 'Incident Response')
@section('page-title', 'Incident Response Center')

@section('content')
<div class="stats-grid">
    <div class="stat-card"><div class="stat-value">{{ $criticalIncidents ?? 0 }}</div><div class="stat-label">Critical</div></div>
    <div class="stat-card"><div class="stat-value">{{ $mtResponse ?? 12 }}min</div><div class="stat-label">MT Response</div></div>
    <div class="stat-card"><div class="stat-value">{{ $mtRecovery ?? 45 }}min</div><div class="stat-label">MT Recovery</div></div>
</div>

<div class="action-buttons">
    <a href="{{ route('incident-responder.runbooks') }}" class="btn btn-primary">📘 Runbooks</a>
    <a href="{{ route('incident-responder.forensics') }}" class="btn btn-secondary">🔬 Forensics</a>
    <a href="{{ route('incident-responder.containment') }}" class="btn btn-danger">🛡️ Containment</a>
</div>
@endsection