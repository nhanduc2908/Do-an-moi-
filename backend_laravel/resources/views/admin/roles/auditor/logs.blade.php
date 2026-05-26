@extends('admin.layouts.app')

@section('title', 'System Logs')
@section('page-title', 'Audit Log Viewer')

@section('content')
<div class="filter-bar">
    <select id="logChannel"><option>laravel</option><option>security</option><option>audit</option><option>api</option></select>
    <button id="viewLogsBtn" class="btn btn-primary">View Logs</button>
    <button id="exportLogsBtn" class="btn btn-secondary">Export</button>
</div>
<pre id="logContent" class="log-viewer"></pre>

@push('scripts')
<script>
    document.getElementById('viewLogsBtn').addEventListener('click', async () => {
        let channel = document.getElementById('logChannel').value;
        let res = await fetch(`/auditor/logs?channel=${channel}`);
        let data = await res.json();
        document.getElementById('logContent').textContent = data.logs.join('\n');
    });
</script>
@endpush
@endsection