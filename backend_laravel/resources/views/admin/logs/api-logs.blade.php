@extends('admin.layouts.app')

@section('title', 'API Logs')
@section('page-title', 'API Request Logs')

@section('content')
<div class="filter-bar">
    <select id="endpointFilter" class="form-control">
        <option value="">All Endpoints</option>
        @foreach($endpoints as $endpoint)
        <option value="{{ $endpoint }}">{{ $endpoint }}</option>
        @endforeach
    </select>
    <select id="statusFilter" class="form-control">
        <option value="">All Status</option>
        <option value="2xx">2xx Success</option>
        <option value="4xx">4xx Client Error</option>
        <option value="5xx">5xx Server Error</option>
    </select>
    <input type="text" id="apiKeySearch" class="form-control" placeholder="API Key">
    <button id="filterBtn" class="btn btn-primary">Filter</button>
</div>

<div class="api-stats">
    <div class="stat-card">
        <div class="stat-value">{{ $totalRequests }}</div>
        <div class="stat-label">Total Requests</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $avgResponseTime }}ms</div>
        <div class="stat-label">Avg Response Time</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $errorRate }}%</div>
        <div class="stat-label">Error Rate</div>
    </div>
</div>

<div class="logs-table">
    <table class="data-table">
        <thead>
            <tr><th>Time</th><th>API Key</th><th>Method</th><th>Endpoint</th><th>Status</th><th>Duration</th><th>Actions</th></tr>
        </thead>
        <tbody id="apiBody">
            @foreach($apiLogs as $log)
            <tr>
                <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                <td>{{ $log->api_key_id ?? 'Anonymous' }}</td>
                <td>{{ $log->method }}</td>
                <td>{{ Str::limit($log->endpoint, 50) }}</td>
                <td><span class="badge badge-{{ $log->status_code < 400 ? 'success' : 'danger' }}">{{ $log->status_code }}</span></td>
                <td>{{ $log->duration_ms }}ms</td>
                <td><button class="btn btn-sm" onclick="viewApiDetails('{{ $log->id }}')">Details</button></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $apiLogs->links() }}
</div>

@push('scripts')
<script>
    function viewApiDetails(id) {
        fetch(`/admin/logs/api/${id}`)
            .then(response => response.json())
            .then(data => {
                alert(JSON.stringify(data, null, 2));
            });
    }
    
    document.getElementById('filterBtn').addEventListener('click', function() {
        const params = new URLSearchParams({
            endpoint: document.getElementById('endpointFilter').value,
            status_type: document.getElementById('statusFilter').value,
            api_key: document.getElementById('apiKeySearch').value
        });
        window.location.search = params.toString();
    });
</script>
@endpush
@endsection