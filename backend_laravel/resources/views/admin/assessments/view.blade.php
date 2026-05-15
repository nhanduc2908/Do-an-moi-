@extends('admin.layouts.app')

@section('title', 'Assessment Details')
@section('page-title', 'Assessment: {{ $assessment->id }}')

@section('content')
<div class="assessment-header">
    <div class="header-info">
        <h3>{{ ucfirst($assessment->assessment_type) }} Assessment</h3>
        <p>Status: <span class="badge badge-{{ $assessment->status }}">{{ $assessment->status }}</span></p>
        <p>Progress: {{ $assessment->progress }}%</p>
        <p>Score: <strong>{{ $assessment->score ?? 'Pending' }}</strong></p>
    </div>
    <div class="header-actions">
        @if($assessment->status === 'in_progress')
        <a href="{{ route('admin.assessments.continue', $assessment) }}" class="btn btn-primary">Continue Assessment</a>
        @endif
        <button onclick="exportAssessment()" class="btn btn-secondary">Export Report</button>
    </div>
</div>

<div class="assessment-content">
    <div class="summary-section">
        <h4>Summary</h4>
        <p>{{ $assessment->description ?? 'No description provided.' }}</p>
    </div>
    
    <div class="criteria-section">
        <h4>Assessment Criteria</h4>
        <div class="criteria-progress">
            @foreach($criteria as $item)
            <div class="criteria-item">
                <div class="criteria-info">
                    <span class="criteria-name">{{ $item->criteria->code }}: {{ $item->criteria->name }}</span>
                    <span class="criteria-status">
                        @if($item->status === 'completed')
                        <span class="badge badge-success">Completed</span>
                        @elseif($item->status === 'pending')
                        <span class="badge badge-warning">Pending</span>
                        @else
                        <span class="badge badge-secondary">Not Started</span>
                        @endif
                    </span>
                </div>
                @if($item->score)
                <div class="criteria-score">Score: {{ $item->score }}/{{ $item->criteria->max_score }}</div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    
    <div class="findings-section">
        <h4>Findings</h4>
        @if($assessment->findings)
        <ul>
            @foreach($assessment->findings as $finding)
            <li>{{ $finding }}</li>
            @endforeach
        </ul>
        @else
        <p>No findings recorded yet.</p>
        @endif
    </div>
    
    <div class="recommendations-section">
        <h4>Recommendations</h4>
        @if($assessment->recommendations)
        <ul>
            @foreach($assessment->recommendations as $rec)
            <li>{{ $rec }}</li>
            @endforeach
        </ul>
        @else
        <p>No recommendations available.</p>
        @endif
    </div>
</div>

@push('scripts')
<script>
    function exportAssessment() {
        window.location.href = "{{ route('admin.assessments.export', $assessment) }}";
    }
</script>
@endpush
@endsection