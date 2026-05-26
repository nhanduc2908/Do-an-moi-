@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Security Dashboard')

@section('content')
<div class="alert alert-info">ℹ️ You have view-only access</div>

<div class="stats-grid">
    <div class="stat-card"><div class="stat-value">{{ $securityScore ?? 78 }}</div><div class="stat-label">Security Score</div></div>
    <div class="stat-card"><div class="stat-value">{{ $openIncidents ?? 12 }}</div><div class="stat-label">Open Incidents</div></div>
    <div class="stat-card"><div class="stat-value">{{ $totalVulnerabilities ?? 47 }}</div><div class="stat-label">Vulnerabilities</div></div>
</div>

<div class="action-buttons">
    <a href="{{ route('viewer.security-dashboard') }}" class="btn btn-primary">📊 Security Dashboard</a>
    <a href="{{ route('viewer.reports') }}" class="btn btn-secondary">📄 View Reports</a>
</div>
@endsection