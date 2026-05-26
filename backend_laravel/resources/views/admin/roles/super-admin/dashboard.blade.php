@extends('admin.layouts.app')

@section('title', 'Super Admin Dashboard')
@section('page-title', 'Super Administrator Console')

@section('content')
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-value">{{ $totalUsers ?? 0 }}</div>
        <div class="stat-label">Total Users</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $totalRoles ?? 10 }}</div>
        <div class="stat-label">System Roles</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $systemHealth ?? 98 }}%</div>
        <div class="stat-label">System Health</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $activeSessions ?? 0 }}</div>
        <div class="stat-label">Active Sessions</div>
    </div>
</div>

<div class="widget">
    <div class="widget-title">System Overview</div>
    <canvas id="systemChart" height="300"></canvas>
</div>

<div class="action-grid">
    <a href="{{ route('super-admin.system-monitor') }}" class="action-card">📊 System Monitor</a>
    <a href="{{ route('super-admin.audit-trail') }}" class="action-card">📋 Audit Trail</a>
    <a href="{{ route('super-admin.master-control') }}" class="action-card">🎛️ Master Control</a>
    <a href="{{ route('super-admin.backups') }}" class="action-card">💾 Backups</a>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    new Chart(document.getElementById('systemChart'), {
        type: 'line',
        data: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [{
                label: 'System Load',
                data: [45, 52, 48, 55, 50, 42, 38],
                borderColor: '#dc3545',
                fill: true
            }]
        }
    });
</script>
@endpush
@endsection