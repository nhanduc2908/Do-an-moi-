@extends('admin.layouts.app')

@section('title', 'Compliance Dashboard')
@section('page-title', 'Compliance Management')

@section('content')
<div class="stats-grid">
    <div class="stat-card"><div class="stat-value">{{ $isoCompliance ?? 85 }}%</div><div class="stat-label">ISO 27001</div></div>
    <div class="stat-card"><div class="stat-value">{{ $gdprCompliance ?? 78 }}%</div><div class="stat-label">GDPR</div></div>
    <div class="stat-card"><div class="stat-value">{{ $pciCompliance ?? 72 }}%</div><div class="stat-label">PCI DSS</div></div>
</div>

<div class="action-buttons">
    <a href="{{ route('compliance-officer.audit-schedule') }}" class="btn btn-primary">📅 Audit Schedule</a>
    <a href="{{ route('compliance-officer.evidence') }}" class="btn btn-secondary">📎 Evidence Collection</a>
    <a href="{{ route('compliance-officer.reports') }}" class="btn btn-info">📄 Compliance Reports</a>
</div>
@endsection