@extends('admin.layouts.app')

@section('title', 'Compliance Dashboard')
@section('page-title', 'Compliance Management')

@section('content')
<div class="compliance-grid">
    @foreach($standards as $standard)
    <div class="compliance-card">
        <div class="card-header">
            <h3>{{ $standard->standard_name }}</h3>
            <span class="compliance-score">{{ $standard->compliance_percentage }}%</span>
        </div>
        <div class="progress-bar">
            <div class="progress-fill" style="width: {{ $standard->compliance_percentage }}%"></div>
        </div>
        <div class="card-stats">
            <div class="stat">Passed: {{ $standard->passed_controls }}</div>
            <div class="stat">Failed: {{ $standard->failed_controls }}</div>
            <div class="stat">Partial: {{ $standard->partial_controls }}</div>
        </div>
        <div class="card-actions">
            <a href="{{ route('admin.compliance.show', $standard->id) }}" class="btn btn-sm btn-primary">View Details</a>
            <a href="{{ route('admin.compliance.export', $standard->id) }}" class="btn btn-sm btn-secondary">Export Report</a>
        </div>
    </div>
    @endforeach
</div>

<div class="compliance-summary">
    <h3>Recent Audit Findings</h3>
    <table class="data-table">
        <thead>
            <tr><th>Standard</th><th>Control</th><th>Status</th><th>Finding</th><th>Due Date</th></tr>
        </thead>
        <tbody>
            @foreach($recentFindings as $finding)
            <tr>
                <td>{{ $finding->standard_name }}</td>
                <td>{{ $finding->control_id }}</td>
                <td><span class="badge badge-{{ $finding->status }}">{{ $finding->status }}</span></td>
                <td>{{ Str::limit($finding->finding, 50) }}</td>
                <td>{{ $finding->due_date ? $finding->due_date->format('Y-m-d') : '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection