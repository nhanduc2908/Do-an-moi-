@extends('admin.layouts.app')

@section('title', 'Analyst Dashboard')
@section('page-title', 'Security Analysis')

@section('content')
<div class="stats-grid">
    <div class="stat-card"><div class="stat-value">{{ $todayThreats ?? 0 }}</div><div class="stat-label">Threats Today</div></div>
    <div class="stat-card"><div class="stat-value">{{ $falsePositives ?? 0 }}</div><div class="stat-label">False Positives</div></div>
    <div class="stat-card"><div class="stat-value">{{ $mitigationRate ?? 94 }}%</div><div class="stat-label">Mitigation Rate</div></div>
</div>

<div class="action-buttons">
    <a href="{{ route('security-analyst.threat-hunting') }}" class="btn btn-danger">🔍 Threat Hunting</a>
    <a href="{{ route('security-analyst.siem') }}" class="btn btn-info">📊 SIEM Dashboard</a>
    <a href="{{ route('security-analyst.iocs') }}" class="btn btn-secondary">⚠️ IOC Management</a>
</div>
@endsection