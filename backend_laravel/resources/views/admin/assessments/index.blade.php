@extends('admin.layouts.app')

@section('title', 'Assessments')
@section('page-title', 'Security Assessments')

@section('content')
<div class="toolbar">
    <a href="{{ route('admin.assessments.create') }}" class="btn btn-primary">+ New Assessment</a>
</div>

<div class="stats-row">
    <div class="stat-card">
        <div class="stat-value">{{ $totalAssessments }}</div>
        <div class="stat-label">Total Assessments</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $completedAssessments }}</div>
        <div class="stat-label">Completed</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $averageScore }}%</div>
        <div class="stat-label">Average Score</div>
    </div>
</div>

<div class="assessments-list">
    <table class="data-table">
        <thead>
            <tr><th>ID</th><th>Type</th><th>Status</th><th>Progress</th><th>Score</th><th>Assigned To</th><th>Due Date</th><th>Actions</th></tr>
        </thead>
        <tbody>
            @foreach($assessments as $assessment)
            <tr>
                <td>{{ $assessment->id }}</td>
                <td>{{ ucfirst($assessment->assessment_type) }}</td>
                <td><span class="badge badge-{{ $assessment->status }}">{{ $assessment->status }}</span></td>
                <td>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: {{ $assessment->progress }}%"></div>
                        <span class="progress-text">{{ $assessment->progress }}%</span>
                    </div>
                </td>
                <td>{{ $assessment->score ?? '-' }}</td>
                <td>{{ $assessment->assignedUser->name ?? 'Unassigned' }}</td>
                <td>{{ $assessment->due_date ? $assessment->due_date->format('Y-m-d') : '-' }}</td>
                <td class="action-buttons">
                    <a href="{{ route('admin.assessments.view', $assessment) }}" class="btn btn-sm">View</a>
                    @if($assessment->status === 'in_progress')
                    <a href="{{ route('admin.assessments.continue', $assessment) }}" class="btn btn-sm">Continue</a>
                    @endif
                    @if($assessment->status === 'submitted')
                    <a href="{{ route('admin.assessments.review', $assessment) }}" class="btn btn-sm">Review</a>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $assessments->links() }}
</div>
@endsection