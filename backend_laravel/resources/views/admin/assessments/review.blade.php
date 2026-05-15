@extends('admin.layouts.app')

@section('title', 'Review Assessment')
@section('page-title', 'Review Assessment: {{ $assessment->id }}')

@section('content')
<form method="POST" action="{{ route('admin.assessments.review.submit', $assessment) }}">
    @csrf
    
    <div class="review-content">
        @foreach($details as $detail)
        <div class="review-item">
            <div class="review-question">
                <h4>{{ $detail->criteria->code }}: {{ $detail->criteria->name }}</h4>
                <p>{{ $detail->criteria->description }}</p>
            </div>
            
            <div class="review-response">
                <label>Response:</label>
                <div class="response-text">{{ $detail->response ?? 'No response provided' }}</div>
            </div>
            
            @if($detail->evidence)
            <div class="review-evidence">
                <label>Evidence:</label>
                <a href="{{ Storage::url($detail->evidence) }}" target="_blank">View Evidence</a>
            </div>
            @endif
            
            <div class="review-score">
                <label>Score:</label>
                <select name="scores[{{ $detail->id }}]" class="form-control" style="width: 100px;">
                    @for($i = 0; $i <= $detail->criteria->max_score; $i++)
                    <option value="{{ $i }}" {{ $detail->score == $i ? 'selected' : '' }}>
                        {{ $i }}/{{ $detail->criteria->max_score }}
                    </option>
                    @endfor
                </select>
            </div>
            
            <div class="review-comment">
                <label>Reviewer Comment:</label>
                <textarea name="comments[{{ $detail->id }}]" class="form-control" rows="2">{{ $detail->comments }}</textarea>
            </div>
        </div>
        @endforeach
    </div>
    
    <div class="review-summary">
        <h3>Overall Assessment</h3>
        <div class="form-group">
            <label>Overall Status</label>
            <select name="status" class="form-control" required>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected - Needs Rework</option>
                <option value="conditional">Conditionally Approved</option>
            </select>
        </div>
        
        <div class="form-group">
            <label>Reviewer Notes</label>
            <textarea name="reviewer_notes" class="form-control" rows="4"></textarea>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Submit Review</button>
            <a href="{{ route('admin.assessments.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </div>
</form>
@endsection