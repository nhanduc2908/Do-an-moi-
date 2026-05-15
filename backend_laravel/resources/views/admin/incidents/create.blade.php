@extends('admin.layouts.app')

@section('title', 'Report Incident')
@section('page-title', 'Report New Security Incident')

@section('content')
<form method="POST" action="{{ route('admin.incidents.store') }}">
    @csrf
    
    <div class="form-group">
        <label class="form-label">Incident Title *</label>
        <input type="text" name="title" class="form-control" required>
    </div>
    
    <div class="form-group">
        <label class="form-label">Description *</label>
        <textarea name="description" class="form-control" rows="5" required></textarea>
    </div>
    
    <div class="form-row">
        <div class="form-group">
            <label class="form-label">Severity *</label>
            <select name="severity" class="form-control" required>
                <option value="critical">Critical - Major impact</option>
                <option value="high">High - Significant impact</option>
                <option value="medium">Medium - Moderate impact</option>
                <option value="low">Low - Minor impact</option>
            </select>
        </div>
        
        <div class="form-group">
            <label class="form-label">Category</label>
            <select name="category" class="form-control">
                <option value="malware">Malware</option>
                <option value="phishing">Phishing</option>
                <option value="unauthorized_access">Unauthorized Access</option>
                <option value="data_breach">Data Breach</option>
                <option value="dos">Denial of Service</option>
                <option value="other">Other</option>
            </select>
        </div>
    </div>
    
    <div class="form-group">
        <label class="form-label">Detection Time</label>
        <input type="datetime-local" name="detected_at" class="form-control" value="{{ now()->format('Y-m-d\TH:i') }}">
    </div>
    
    <div class="form-group">
        <label class="checkbox-label">
            <input type="checkbox" name="notify_team" value="1" checked>
            Notify security team immediately
        </label>
    </div>
    
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Report Incident</button>
        <a href="{{ route('admin.incidents.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
</form>
@endsection