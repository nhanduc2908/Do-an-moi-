@extends('admin.layouts.app')

@section('title', 'Access Logs')
@section('page-title', 'System Access Logs')

@section('content')
<div class="stats-row">
    <div class="stat-card">
        <div class="stat-value">{{ $totalAccesses }}</div>
        <div class="stat-label">Total Access Events</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $uniqueIPs }}</div>
        <div class="stat-label">Unique IPs</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $failedAccesses }}</div>
        <div class="stat-label">Failed Attempts</div>
    </div>
</div>

<div class="filter-bar">
    <select id="statusFilter" class="form-control">
        <option value="">All Status</option>
        <option value="granted">Granted</option>
        <option value="denied">Denied</option>
    </select>
    <select id="methodFilter" class="form-control">
        <option value="">All Methods</option>
        <option value="GET">GET</option>
        <option value="POST">POST</option>
        <option value="PUT">PUT</option>
        <option value="DELETE">DELETE</option>
    </select>
    <input type="text" id="ipSearch" class="form-control" placeholder="IP Address">
    <button id="filterBtn" class="btn btn-primary">Filter</button>
</div>

<div class="logs-table">
    <table class="data-table">
        <thead>
            <tr><th>Time</th><th>IP Address</th><th>Method</th><th>URL</th><th>Status</th><th>User Agent</th></tr>
        </thead>
        <tbody id="accessBody">
            @foreach($accessLogs as $log)
            <tr>
                <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                <td>{{ $log->ip_address }}</td>
                <td>{{ $log->method }}</td>
                <td>{{ Str::limit($log->url, 60) }}</td>
                <td><span class="badge badge-{{ $log->status_code == 200 ? 'success' : 'danger' }}">{{ $log->status_code }}</span></td>
                <td>{{ Str::limit($log->user_agent, 50) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $accessLogs->links() }}
</div>

@push('scripts')
<script>
    document.getElementById('filterBtn').addEventListener('click', function() {
        const params = new URLSearchParams({
            status: document.getElementById('statusFilter').value,
            method: document.getElementById('methodFilter').value,
            ip: document.getElementById('ipSearch').value
        });
        window.location.search = params.toString();
    });
</script>
@endpush
@endsection