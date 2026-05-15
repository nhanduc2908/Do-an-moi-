@extends('admin.layouts.app')

@section('title', 'Sync Logs')
@section('page-title', 'Synchronization Logs')

@section('content')
<div class="filter-bar">
    <select id="typeFilter" class="form-control">
        <option value="">All Types</option>
        <option value="flutter">Flutter</option>
        <option value="firebase">Firebase</option>
        <option value="websocket">WebSocket</option>
    </select>
    <select id="statusFilter" class="form-control">
        <option value="">All Status</option>
        <option value="success">Success</option>
        <option value="failed">Failed</option>
        <option value="pending">Pending</option>
    </select>
    <input type="date" id="dateFrom" class="form-control">
    <input type="date" id="dateTo" class="form-control">
    <button id="filterBtn" class="btn btn-primary">Filter</button>
    <button id="exportBtn" class="btn btn-secondary">Export</button>
</div>

<div class="logs-list">
    <table class="data-table">
        <thead>
            <tr><th>Time</th><th>Type</th><th>Status</th><th>Items Synced</th><th>Duration</th><th>Error Message</th><th>Details</th></tr>
        </thead>
        <tbody id="logsBody">
            @include('admin.sync.partials.logs_rows', ['logs' => $logs])
        </tbody>
    </table>
    <div id="pagination">{{ $logs->links() }}</div>
</div>

@push('scripts')
<script>
    document.getElementById('filterBtn').addEventListener('click', applyFilters);
    document.getElementById('exportBtn').addEventListener('click', exportLogs);
    
    async function applyFilters() {
        const params = new URLSearchParams({
            type: document.getElementById('typeFilter').value,
            status: document.getElementById('statusFilter').value,
            from: document.getElementById('dateFrom').value,
            to: document.getElementById('dateTo').value
        });
        
        const response = await fetch(`/admin/sync/logs/filter?${params}`);
        const html = await response.text();
        document.getElementById('logsBody').innerHTML = html;
    }
    
    async function exportLogs() {
        const params = new URLSearchParams({
            type: document.getElementById('typeFilter').value,
            status: document.getElementById('statusFilter').value,
            from: document.getElementById('dateFrom').value,
            to: document.getElementById('dateTo').value,
            format: 'csv'
        });
        
        window.location.href = `/admin/sync/logs/export?${params}`;
    }
</script>
@endpush
@endsection