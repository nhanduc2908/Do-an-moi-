@extends('admin.layouts.app')

@section('title', 'Incidents')
@section('page-title', 'Security Incidents')

@section('content')
<div class="toolbar">
    <a href="{{ route('admin.incidents.create') }}" class="btn btn-primary">+ Report Incident</a>
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

<div class="incidents-list">
    <table class="data-table">
        <thead>
            <tr><th>Code</th><th>Title</th><th>Severity</th><th>Status</th><th>Detected</th><th>Assigned To</th><th>Actions</th></tr>
        </thead>
        <tbody>
            @foreach($incidents as $incident)
            <tr class="severity-{{ strtolower($incident->severity) }}">
                <td>{{ $incident->incident_code }}</td>
                <td>{{ $incident->title }}</td>
                <td><span class="badge badge-{{ strtolower($incident->severity) }}">{{ $incident->severity }}</span></td>
                <td><span class="badge badge-{{ $incident->status }}">{{ $incident->status }}</span></td>
                <td>{{ $incident->detected_at->format('Y-m-d H:i') }}</td>
                <td>{{ $incident->assignee->name ?? 'Unassigned' }}</td>
                <td class="action-buttons">
                    <a href="{{ route('admin.incidents.view', $incident) }}" class="btn btn-sm">View</a>
                    @if($incident->status !== 'resolved')
                    <a href="{{ route('admin.incidents.resolve', $incident) }}" class="btn btn-sm">Resolve</a>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $incidents->links() }}
</div>
@endsection