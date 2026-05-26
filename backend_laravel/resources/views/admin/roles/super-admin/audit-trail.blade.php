@extends('admin.layouts.app')

@section('title', 'Audit Trail')
@section('page-title', 'System Audit Trail')

@section('content')
<div class="filter-bar">
    <form method="GET" class="filter-form">
        <select name="event_type" class="form-control">
            <option value="">All Events</option>
            @foreach($eventTypes ?? [] as $type)
            <option value="{{ $type }}">{{ ucfirst($type) }}</option>
            @endforeach
        </select>
        <select name="severity" class="form-control">
            <option value="">All Severities</option>
            @foreach($severities ?? [] as $sev)
            <option value="{{ $sev }}">{{ ucfirst($sev) }}</option>
            @endforeach
        </select>
        <input type="date" name="from" class="form-control" value="{{ request('from') }}">
        <input type="date" name="to" class="form-control" value="{{ request('to') }}">
        <button type="submit" class="btn btn-primary">Filter</button>
    </form>
</div>

<div class="audit-table">
    <table class="data-table">
        <thead><tr><th>Time</th><th>Event</th><th>Severity</th><th>User</th><th>IP</th><th>Message</th></tr></thead>
        <tbody>
            @forelse($auditEvents ?? [] as $event)
            <tr class="severity-{{ $event->severity }}">
                <td>{{ $event->logged_at->format('Y-m-d H:i:s') }}</td>
                <td>{{ $event->event_type }}</td>
                <td><span class="badge badge-{{ $event->severity }}">{{ $event->severity }}</span></td>
                <td>{{ $event->user?->name ?? 'System' }}</td>
                <td>{{ $event->ip_address }}</td>
                <td>{{ Str::limit($event->message, 80) }}</td>
            </tr>
            @empty
            <tr><td colspan="6">No records found</td></tr>
            @endforelse
        </tbody>
    </table>
    {{ $auditEvents->links() }}
</div>
@endsection