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

<div class="admin-grid">
    <div class="widget">
        <div class="widget-title">System Overview</div>
        <canvas id="systemChart" height="250"></canvas>
    </div>
    
    <div class="widget">
        <div class="widget-title">Quick Actions</div>
        <div class="action-grid">
            <a href="{{ route('admin.users.index') }}" class="action-card">
                <span class="action-icon">👥</span>
                <span>Manage Users</span>
            </a>
            <a href="{{ route('admin.roles.index') }}" class="action-card">
                <span class="action-icon">🎭</span>
                <span>Manage Roles</span>
            </a>
            <a href="{{ route('admin.settings.general') }}" class="action-card">
                <span class="action-icon">⚙️</span>
                <span>System Settings</span>
            </a>
            <a href="{{ route('admin.backup.run') }}" class="action-card">
                <span class="action-icon">💾</span>
                <span>Run Backup</span>
            </a>
            <a href="{{ route('admin.logs.audit') }}" class="action-card">
                <span class="action-icon">📋</span>
                <span>Audit Logs</span>
            </a>
            <a href="{{ route('admin.system.status') }}" class="action-card">
                <span class="action-icon">📊</span>
                <span>System Status</span>
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    new Chart(document.getElementById('systemChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode($chartLabels ?? ['Week 1', 'Week 2', 'Week 3', 'Week 4']) !!},
            datasets: [{
                label: 'System Load',
                data: {!! json_encode($systemLoad ?? [45, 52, 48, 55]) !!},
                borderColor: '#dc3545',
                fill: true
            }]
        }
    });
</script>
@endpush
@endsection