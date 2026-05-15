@extends('admin.layouts.app')

@section('title', 'User Details')
@section('page-title', 'User Details: {{ $user->name }}')

@section('content')
<div class="user-profile">
    <div class="profile-header">
        <div class="avatar">{{ substr($user->name, 0, 1) }}</div>
        <div class="profile-info">
            <h2>{{ $user->name }}</h2>
            <p>{{ $user->email }}</p>
            <p>Role: {{ $user->roles->first()->display_name ?? 'No role' }}</p>
        </div>
    </div>
    
    <div class="profile-details">
        <div class="detail-group">
            <h3>Account Information</h3>
            <table class="info-table">
                <tr><th>Created:</th><td>{{ $user->created_at->format('Y-m-d H:i') }}</td></tr>
                <tr><th>Last Login:</th><td>{{ $user->last_login_at ? $user->last_login_at->format('Y-m-d H:i') : 'Never' }}</td></tr>
                <tr><th>Status:</th><td><span class="badge badge-{{ $user->status }}">{{ $user->status }}</span></td></tr>
                <tr><th>Department:</th><td>{{ $user->department ?? '-' }}</td></tr>
            </table>
        </div>
        
        <div class="detail-group">
            <h3>Activity Log</h3>
            <table class="data-table">
                <thead><tr><th>Time</th><th>Action</th><th>IP Address</th></tr></thead>
                <tbody>
                    @forelse($activities as $activity)
                    <tr>
                        <td>{{ $activity->created_at->format('Y-m-d H:i') }}</td>
                        <td>{{ $activity->activity_type }}</td>
                        <td>{{ $activity->ip_address }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3">No activity recorded</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="form-actions">
        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">Edit User</a>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Back</a>
    </div>
</div>
@endsection