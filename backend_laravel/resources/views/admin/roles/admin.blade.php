@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')
@section('page-title', 'Administration Panel')

@section('content')
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-value">{{ $totalUsers ?? 0 }}</div>
        <div class="stat-label">Users</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $totalAssessments ?? 0 }}</div>
        <div class="stat-label">Assessments</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $pendingReviews ?? 0 }}</div>
        <div class="stat-label">Pending Reviews</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $activeIncidents ?? 0 }}</div>
        <div class="stat-label">Active Incidents</div>
    </div>
</div>

<div class="admin-grid">
    <div class="widget">
        <div class="widget-title">Recent Activity</div>
        <table class="data-table">
            <thead><tr><th>User</th><th>Action</th><th>Time</th></tr></thead>
            <tbody>
                @forelse($activities ?? [] as $activity)
                <tr>
                    <td>{{ $activity->user_name ?? 'System' }}</td>
                    <td>{{ $activity->action ?? '-' }}</td>
                    <td>{{ $activity->created_at->diffForHumans() ?? '-' }}</td>
                </tr>
                @empty
                <tr><td colspan="3">No recent activity</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="widget">
        <div class="widget-title">Quick Actions</div>
        <div class="action-buttons">
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">➕ Add User</a>
            <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">🎭 Manage Roles</a>
            <a href="{{ route('admin.settings.general') }}" class="btn btn-info">⚙️ Settings</a>
        </div>
    </div>
</div>
@endsection