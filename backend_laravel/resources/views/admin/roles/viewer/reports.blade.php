@extends('admin.layouts.app')

@section('title', 'Reports')
@section('page-title', 'Security Reports')

@section('content')
<table class="data-table">
    <thead><tr><th>Report Name</th><th>Type</th><th>Date</th><th>Actions</th></tr></thead>
    <tbody>
        @foreach($reports ?? [] as $report)
        <tr><td>{{ $report->report_name }}</td><td>{{ $report->report_type }}</td><td>{{ $report->generated_at->format('Y-m-d') }}</td><td><a href="{{ route('viewer.report.view', $report->id) }}" class="btn btn-sm">View</a></td></tr>
        @endforeach
    </tbody>
</table>
@endsection