@extends('admin.layouts.app')

@section('title', 'Auditor Dashboard')
@section('page-title', 'Audit & Compliance')

@section('content')
<div class="stats-grid">
    <div class="stat-card"><div class="stat-value">{{ $totalAuditEvents ?? 0 }}</div><div class="stat-label">Total Events</div></div>
    <div class="stat-card"><div class="stat-value">{{ $auditRetention ?? 365 }}</div><div class="stat-label">Retention (days)</div></div>
</div>

<div class="action-buttons">
    <a href="{{ route('auditor.logs') }}" class="btn btn-primary">📄 System Logs</a>
    <a href="{{ route('auditor.access-reports') }}" class="btn btn-secondary">🔐 Access Reports</a>
    <a href="{{ route('auditor.compliance') }}" class="btn btn-info">✓ Compliance Checker</a>
</div>
@endsection