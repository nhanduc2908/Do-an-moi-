@extends('admin.layouts.app')

@section('title', 'Compliance Reports')
@section('page-title', 'Compliance Reports')

@section('content')
<div class="toolbar"><button class="btn btn-primary" onclick="generateReport()">Generate Report</button></div>
<table class="data-table">
    <thead><tr><th>Report Name</th><th>Standard</th><th>Date</th><th>Actions</th></tr></thead>
    <tbody>
        @foreach($reports ?? [] as $report)
        <tr><td>{{ $report->name }}</td><td>{{ $report->standard }}</td><td>{{ $report->created_at }}</td><td><button class="btn btn-sm">Download</button></td></tr>
        @endforeach
    </tbody>
</table>
@endsection