@extends('admin.layouts.app')

@section('title', 'Resolve Incident')
@section('page-title', 'Resolve Incident: {{ $incident->incident_code }}')

@section('content')
<form method="POST" action="{{ route('admin.incidents.resolve.submit', $incident) }}">
    @csrf
    
    <div class="form-group">
        <label class="form-label">Resolution Summary *</label>
        <textarea name="resolution_summary" class="form-control" rows="5" required></textarea>
    </div>
    
    <div class="form-group">
        <label class="form-label">Root Cause</label>
        <textarea name="root_cause" class="form-control" rows="3"></textarea>
    </div>
    
    <div class="form-group">
        <label class="form-label">Preventive Measures</label>
     <textarea name="preventive_measures" class="form-control" rows="3" placeholder="Measures taken to prevent recurrence"></textarea>
    </div>
    
    <div class="form-group">
        <label class="checkbox-label">
            <input type="checkbox" name="generate_report" value="1" checked>
            Generate incident report after resolution
        </label>
    </div>
    
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Mark as Resolved</button>
        <a href="{{ route('admin.incidents.view', $incident) }}" class="btn btn-secondary">Cancel</a>
    </div>
</form>
@endsection