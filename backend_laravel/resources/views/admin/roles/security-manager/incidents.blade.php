@extends('admin.layouts.app')

@section('title', 'Incident Management')
@section('page-title', 'Security Incidents')

@section('content')
<div class="filter-bar">
    <select id="severityFilter" class="form-control"><option value="">All Severities</option><option value="critical">Critical</option><option value="high">High</option><option value="medium">Medium</option><option value="low">Low</option></select>
    <button id="assignBtn" class="btn btn-primary">Assign Selected</button>
</div>

<table class="data-table">
    <thead><tr><th><input type="checkbox" id="selectAll"></th><th>Code</th><th>Title</th><th>Severity</th><th>Status</th><th>Assigned To</th><th>Actions</th></tr></thead>
    <tbody>
        @foreach($incidents as $incident)
        <tr><td><input type="checkbox" class="incident-check" value="{{ $incident->id }}"></td><td>{{ $incident->incident_code }}</td><td>{{ $incident->title }}</td><td><span class="badge badge-{{ strtolower($incident->severity) }}">{{ $incident->severity }}</span></td><td>{{ $incident->status }}</td><td>{{ $incident->assignee?->name ?? 'Unassigned' }}</td><td><button class="btn btn-sm" onclick="escalate('{{ $incident->id }}')">Escalate</button></td></tr>
        @endforeach
    </tbody>
</table>

@push('scripts')
<script>
    function escalate(id) { if(confirm('Escalate this incident?')) alert('Escalated'); }
</script>
@endpush
@endsection