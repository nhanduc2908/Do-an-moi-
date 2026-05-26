@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')
@section('page-title', 'Administration Panel')

@section('content')
<div class="stats-grid">
    <div class="stat-card"><div class="stat-value">{{ $totalUsers ?? 0 }}</div><div class="stat-label">Total Users</div></div>
    <div class="stat-card"><div class="stat-value">{{ $totalAssessments ?? 0 }}</div><div class="stat-label">Assessments</div></div>
    <div class="stat-card"><div class="stat-value">{{ $pendingReviews ?? 0 }}</div><div class="stat-label">Pending Reviews</div></div>
    <div class="stat-card"><div class="stat-value">{{ $activeIncidents ?? 0 }}</div><div class="stat-label">Active Incidents</div></div>
</div>

<div class="action-buttons">
    <a href="{{ route('admin.users') }}" class="btn btn-primary">👥 Manage Users</a>
    <a href="{{ route('admin.roles') }}" class="btn btn-secondary">🎭 Manage Roles</a>
    <a href="{{ route('admin.settings') }}" class="btn btn-info">⚙️ Settings</a>
</div>

<div class="widget">
    <div class="widget-title">Recent Users</div>
    <table class="data-table">
        <thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Created</th></tr></thead>
        <tbody>
            @foreach($recentUsers ?? [] as $user)
            <tr><td>{{ $user->name }}</td><td>{{ $user->email }}</td><td>{{ $user->roles->first()->display_name ?? '-' }}</td><td>{{ $user->created_at->diffForHumans() }}</td></tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection