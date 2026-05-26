@extends('admin.layouts.app')

@section('title', 'Auditor Dashboard')
@section('page-title', 'Audit & Compliance Viewer')

@section('content')
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-value">{{ $totalAuditEvents ?? 0 }}</div>
        <div class="stat-label">Total Events</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $auditRetention ?? 365 }}</div>
        <div class="stat-label">Retention (days)</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $lastAudit ?? 'N/A' }}</div>
        <div class="stat-label">Last Audit</div>
    </div>
</div>

<div class="admin-grid">
    <div class="widget">
        <div class="widget-title">Audit Log Viewer</div>
        <div class="filter-bar">
            <input type="date" id="dateFrom" class="form-control" placeholder="From">
            <input type="date" id="dateTo" class="form-control" placeholder="To">
            <select id="eventType" class="form-control">
                <option value="">All Events</option>
                <option value="login">Login</option>
                <option value="assessment">Assessment</option>
                <option value="config">Configuration</option>
            </select>
            <button id="filterBtn" class="btn btn-primary">Filter</button>
        </div>
        <table class="data-table">
            <thead><tr><th>Time</th><th>User</th><th>Event</th><th>Details</th></tr></thead>
            <tbody id="auditTableBody">
                @foreach($auditEvents ?? [] as $event)
                <tr>
                    <td>{{ $event['time'] ?? '-' }}</td>
                    <td>{{ $event['user'] ?? '-' }}</td>
                    <td>{{ $event['event'] ?? '-' }}</td>
                    <td>{{ $event['details'] ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="widget">
        <div class="widget-title">Export Audit Logs</div>
        <div class="export-options">
            <button class="btn btn-secondary" onclick="exportAudit('pdf')">📄 Export PDF</button>
            <button class="btn btn-secondary" onclick="exportAudit('csv')">📊 Export CSV</button>
            <button class="btn btn-secondary" onclick="exportAudit('excel')">📑 Export Excel</button>
        </div>
    </div>
</div>
@endsection