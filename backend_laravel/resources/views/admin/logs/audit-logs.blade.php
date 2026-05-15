@extends('admin.layouts.app')

@section('title', 'Audit Logs')
@section('page-title', 'System Audit Trail')

@section('content')
<div class="audit-summary">
    <div class="stat-card">
        <div class="stat-value">{{ $totalEvents }}</div>
        <div class="stat-label">Total Events</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $uniqueUsers }}</div>
        <div class="stat-label">Active Users</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $eventTypes }}</div>
        <div class="stat-label">Event Types</div>
    </div>
</div>

<div class="filter-bar">
    <select id="actionFilter" class="form-control">
        <option value="">All Actions</option>
        <option value="create">Create</option>
        <option value="update">Update</option>
        <option value="delete">Delete</option>
        <option value="view">View</option>
        <option value="export">Export</option>
    </select>
    <select id="userFilter" class="form-control">
        <option value="">All Users</option>
        @foreach($users as $user)
        <option value="{{ $user->id }}">{{ $user->name }}</option>
        @endforeach
    </select>
    <input type="date" id="dateFrom" class="form-control">
    <input type="date" id="dateTo" class="form-control">
    <button id="filterBtn" class="btn btn-primary">Filter</button>
</div>

<div class="logs-table">
    <table class="data-table">
        <thead>
            <tr><th>Timestamp</th><th>User</th><th>Action</th><th>Resource</th><th>IP Address</th><th>Details</th></tr>
        </thead>
        <tbody id="auditBody">
            @foreach($audits as $audit)
            <tr>
                <td>{{ $audit->created_at->format('Y-m-d H:i:s') }}</td>
                <td>{{ $audit->user ? $audit->user->name : 'System' }}</td>
                <td>{{ $audit->action }}</td>
                <td>{{ $audit->resource_type ?? '-' }}</td>
                <td>{{ $audit->ip_address }}</td>
                <td><button class="btn btn-sm" onclick="viewDetails('{{ $audit->id }}')">View</button></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $audits->links() }}
</div>

<!-- Detail Modal -->
<div id="detailModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Audit Details</h3>
            <button class="close" onclick="closeModal()">&times;</button>
        </div>
        <div class="modal-body" id="detailContent"></div>
    </div>
</div>

@push('scripts')
<script>
    function viewDetails(id) {
        fetch(`/admin/logs/audit/${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('detailContent').innerHTML = `
                    <table class="info-table">
                        <tr><th>Timestamp:</th><td>${data.created_at}</td></tr>
                        <tr><th>User:</th><td>${data.user_name}</td></tr>
                        <tr><th>IP Address:</th><td>${data.ip_address}</td></tr>
                        <tr><th>User Agent:</th><td>${data.user_agent}</td></tr>
                        <tr><th>Action:</th><td>${data.action}</td></tr>
                        <tr><th>Resource:</th><td>${data.resource_type}: ${data.resource_id}</td></tr>
                        <tr><th>Old Values:</th><td><pre>${JSON.stringify(data.old_values, null, 2)}</pre></td></tr>
                        <tr><th>New Values:</th><td><pre>${JSON.stringify(data.new_values, null, 2)}</pre></td></tr>
                    </table>
                `;
                document.getElementById('detailModal').style.display = 'block';
            });
    }
    
    function closeModal() {
        document.getElementById('detailModal').style.display = 'none';
    }
    
    document.getElementById('filterBtn').addEventListener('click', function() {
        const params = new URLSearchParams({
            action: document.getElementById('actionFilter').value,
            user_id: document.getElementById('userFilter').value,
            from: document.getElementById('dateFrom').value,
            to: document.getElementById('dateTo').value
        });
        window.location.search = params.toString();
    });
</script>
@endpush
@endsection