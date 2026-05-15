@extends('admin.layouts.app')

@section('title', 'Vulnerabilities')
@section('page-title', 'Vulnerability Management')

@section('content')
<div class="toolbar">
    <button id="scanBtn" class="btn btn-primary">🔍 Run Scan</button>
    <a href="{{ route('admin.vulnerabilities.export') }}" class="btn btn-secondary">Export</a>
</div>

<div class="stats-row">
    <div class="stat-card critical">
        <div class="stat-value">{{ $criticalCount }}</div>
        <div class="stat-label">Critical</div>
    </div>
    <div class="stat-card high">
        <div class="stat-value">{{ $highCount }}</div>
        <div class="stat-label">High</div>
    </div>
    <div class="stat-card medium">
        <div class="stat-value">{{ $mediumCount }}</div>
        <div class="stat-label">Medium</div>
    </div>
    <div class="stat-card low">
        <div class="stat-value">{{ $lowCount }}</div>
        <div class="stat-label">Low</div>
    </div>
</div>

<div class="filter-bar">
    <select id="severityFilter" class="form-control">
        <option value="">All Severities</option>
        <option value="CRITICAL">Critical</option>
        <option value="HIGH">High</option>
        <option value="MEDIUM">Medium</option>
        <option value="LOW">Low</option>
    </select>
    <select id="statusFilter" class="form-control">
        <option value="">All Status</option>
        <option value="open">Open</option>
        <option value="in_progress">In Progress</option>
        <option value="fixed">Fixed</option>
    </select>
    <input type="text" id="searchInput" class="form-control" placeholder="Search CVEs...">
</div>

<div class="vulnerabilities-list">
    <table class="data-table">
        <thead>
            <tr><th>CVE ID</th><th>Title</th><th>Severity</th><th>CVSS Score</th><th>Status</th><th>Discovered</th><th>Actions</th></tr>
        </thead>
        <tbody>
            @foreach($vulnerabilities as $vuln)
            <tr class="severity-{{ strtolower($vuln->severity) }}">
                <td><strong>{{ $vuln->cve_id }}</strong></td>
                <td>{{ Str::limit($vuln->title, 60) }}</td>
                <td><span class="badge badge-{{ strtolower($vuln->severity) }}">{{ $vuln->severity }}</span></td>
                <td>{{ $vuln->cvss_score }}</td>
                <td><span class="badge badge-{{ $vuln->status }}">{{ $vuln->status }}</span></td>
                <td>{{ $vuln->published_at->format('Y-m-d') }}</td>
                <td class="action-buttons">
                    <a href="{{ route('admin.vulnerabilities.view', $vuln) }}" class="btn btn-sm">View</a>
                    @if($vuln->status === 'open')
                    <a href="{{ route('admin.vulnerabilities.remediate', $vuln) }}" class="btn btn-sm">Remediate</a>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $vulnerabilities->links() }}
</div>

@push('scripts')
<script>
    document.getElementById('scanBtn').addEventListener('click', async function() {
        this.disabled = true;
        this.textContent = 'Scanning...';
        
        const response = await fetch('{{ route("admin.vulnerabilities.scan") }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        });
        
        const data = await response.json();
        if (data.success) {
            location.reload();
        } else {
            alert('Scan failed: ' + data.message);
            this.disabled = false;
            this.textContent = 'Run Scan';
        }
    });
    
    function filterTable() {
        const severity = document.getElementById('severityFilter').value;
        const status = document.getElementById('statusFilter').value;
        const search = document.getElementById('searchInput').value.toLowerCase();
        
        document.querySelectorAll('.vulnerabilities-list tbody tr').forEach(row => {
            let show = true;
            if (severity && !row.cells[2].textContent.includes(severity)) show = false;
            if (status && !row.cells[4].textContent.includes(status)) show = false;
            if (search && !row.textContent.toLowerCase().includes(search)) show = false;
            row.style.display = show ? '' : 'none';
        });
    }
    
    document.getElementById('severityFilter').addEventListener('change', filterTable);
    document.getElementById('statusFilter').addEventListener('change', filterTable);
    document.getElementById('searchInput').addEventListener('keyup', filterTable);
</script>
@endpush
@endsection