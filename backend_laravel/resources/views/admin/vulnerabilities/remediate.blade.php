@extends('admin.layouts.app')

@section('title', 'Remediate Vulnerability')
@section('page-title', 'Remediate: {{ $vulnerability->cve_id }}')

@section('content')
<form method="POST" action="{{ route('admin.vulnerabilities.remediate.submit', $vulnerability) }}">
    @csrf
    
    <div class="remediation-plan">
        <div class="form-group">
            <label class="form-label">Remediation Action *</label>
            <select name="action" class="form-control" required>
                <option value="patch">Apply Patch</option>
                <option value="mitigation">Apply Mitigation</option>
                <option value="accept">Accept Risk</option>
                <option value="exception">Request Exception</option>
            </select>
        </div>
        
        <div class="form-group">
            <label class="form-label">Remediation Details *</label>
            <textarea name="details" class="form-control" rows="5" required placeholder="Describe the steps taken to remediate this vulnerability..."></textarea>
        </div>
        
        <div class="form-group">
            <label class="form-label">Target Completion Date</label>
            <input type="date" name="target_date" class="form-control" value="{{ now()->addDays(7)->format('Y-m-d') }}">
        </div>
        
        <div class="form-group">
            <label class="form-label">Assigned To</label>
            <select name="assigned_to" class="form-control">
                <option value="">Select Team Member</option>
                @foreach($teamMembers as $member)
                <option value="{{ $member->id }}">{{ $member->name }}</option>
                @endforeach
            </select>
        </div>
        
        <div class="alert alert-warning">
            <strong>⚠️ Before proceeding:</strong>
            <ul>
                <li>Test the patch in a staging environment first</li>
                <li>Ensure you have a rollback plan</li>
                <li>Schedule maintenance window if required</li>
            </ul>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Start Remediation</button>
            <a href="{{ route('admin.vulnerabilities.view', $vulnerability) }}" class="btn btn-secondary">Cancel</a>
        </div>
    </div>
</form>
@endsection