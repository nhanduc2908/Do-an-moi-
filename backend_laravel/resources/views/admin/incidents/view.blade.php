@extends('admin.layouts.app')

@section('title', 'Incident Details')
@section('page-title', 'Incident: {{ $incident->incident_code }}')

@section('content')
<div class="incident-header severity-{{ strtolower($incident->severity) }}">
    <div class="header-main">
        <h2>{{ $incident->title }}</h2>
        <div class="badge-group">
            <span class="badge badge-{{ strtolower($incident->severity) }}">{{ $incident->severity }}</span>
            <span class="badge badge-{{ $incident->status }}">{{ $incident->status }}</span>
        </div>
    </div>
    <div class="header-meta">
        <div>Detected: {{ $incident->detected_at->format('Y-m-d H:i:s') }}</div>
        <div>Reported: {{ $incident->reported_at ? $incident->reported_at->format('Y-m-d H:i:s') : '-' }}</div>
        <div>Category: {{ ucfirst($incident->category) }}</div>
    </div>
</div>

<div class="incident-details">
    <div class="detail-section">
        <h3>Description</h3>
        <p>{{ $incident->description }}</p>
    </div>
    
    <div class="detail-section">
        <h3>Timeline</h3>
        <div class="timeline">
            @foreach($timeline as $event)
            <div class="timeline-item">
                <div class="timeline-time">{{ $event['time']->format('Y-m-d H:i:s') }}</div>
                <div class="timeline-content">{{ $event['description'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
    
    <div class="detail-section">
        <h3>Comments</h3>
        <div class="comments-list">
            @foreach($incident->comments as $comment)
            <div class="comment">
                <div class="comment-header">
                    <strong>{{ $comment->user->name }}</strong>
                    <span>{{ $comment->created_at->diffForHumans() }}</span>
                </div>
                <div class="comment-body">{{ $comment->comment }}</div>
            </div>
            @endforeach
        </div>
        
        <form method="POST" action="{{ route('admin.incidents.comment', $incident) }}" class="add-comment">
            @csrf
            <textarea name="comment" class="form-control" rows="3" placeholder="Add a comment..."></textarea>
            <button type="submit" class="btn btn-primary mt-2">Post Comment</button>
        </form>
    </div>
</div>

<div class="form-actions">
    @if($incident->status === 'open')
    <a href="{{ route('admin.incidents.resolve', $incident) }}" class="btn btn-primary">Mark as Resolved</a>
    @endif
    <a href="{{ route('admin.incidents.index') }}" class="btn btn-secondary">Back</a>
</div>
@endsection