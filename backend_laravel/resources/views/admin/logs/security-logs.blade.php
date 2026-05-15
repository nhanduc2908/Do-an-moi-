@extends('admin.layouts.app')

@section('title', 'Security Logs')
@section('page-title', 'Security Event Logs')

@section('content')
<div class="filter-bar">
    <select id="severityFilter" class="form-control">
        <option value="">All Severities</option>
        <option value="critical">Critical</option>
        <option value="high">High</option>
        <option value="medium">Medium</option>
        <option value="low">Low</option>
        <option value="info">Info</option>
    </select>
    <select id="eventFilter" class="form-control">
        <option value="">All Events</option>
        <option value="login">Login</option>
        <option value="logout">Logout</option>
        <option value="failed_login">Failed Login</option>
        <option value="mfa">MFA</option>
        <option value="permission_change">Permission Change</option>
        <option value="key_operation">Key Operation</option>
    </select>
    <input type="text" id="searchInput" class="form-control" placeholder="Search logs...">
    <input type="datetime-local" id="fromDate" class="form-control">
    <input type="datetime-local" id="toDate" class="form-control">
    <button id="filterBtn" class="btn btn-primary">Filter</button>
    <button id="exportBtn" class="btn btn-secondary">Export</button>
</div>

<div class="logs-table">
    <table class="data-table">
        <thead>
            <tr><th>Time</th><th>Severity</th><th>Event Type</th><th>User</th><th>IP Address</th><th>Message</th></tr>
        </thead>
        <tbody id="logsBody">
            @foreach($logs as $log)
            <tr class="severity-{{ strtolower($log->severity) }}">
                <td>{{ $log->logged_at->format('Y-m-d H:i:s') }}</td>
                <td><span class="badge badge-{{ strtolower($log->severity) }}">{{ $log->severity }}</span></td>
                <td>{{ $log->event_type }}</td>
                <td>{{ $log->user ? $log->user->name : 'System' }}</td>
                <td>{{ $log->ip_address }}</td>
                <td>{{ Str::limit($log->message, 80) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $logs->links() }}
</div>

@push('scripts')
<script>
    let searchTimeout;
    
    function applyFilters() {
        const params = new URLSearchParams({
            severity: document.getElementById('severityFilter').value,
            event_type: document.getElementById('eventFilter').value,
            search: document.getElementById('searchInput').value,
            from: document.getElementById('fromDate').value,
            to: document.getElementById('toDate').value
        });
        
        fetch(`/admin/logs/security/filter?${params}`)
            .then(response => response.text())
            .then(html => {
                document.getElementById('logsBody').innerHTML = html;
            });
    }
    
    document.getElementById('filterBtn').addEventListener('click', applyFilters);
    
    document.getElementById('searchInput').addEventListener('keyup', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(applyFilters, 500);
    });
    
    document.getElementById('exportBtn').addEventListener('click', function() {
        const params = new URLSearchParams({
            severity: document.getElementById('severityFilter').value,
            event_type: document.getElementById('eventFilter').value,
            from: document.getElementById('fromDate').value,
            to: document.getElementById('toDate').value,
            format: 'csv'
        });
        window.location.href = `/admin/logs/security/export?${params}`;
    });
</script>
@endpush
@endsection