@extends('admin.layouts.app')

@section('title', 'Add Criteria')
@section('page-title', 'Add New Assessment Criteria')

@section('content')
<form method="POST" action="{{ route('admin.criteria.store') }}">
    @csrf
    
    <div class="form-row">
        <div class="form-group">
            <label class="form-label">Code *</label>
            <input type="text" name="code" class="form-control" required placeholder="e.g., AC-001">
        </div>
        
        <div class="form-group">
            <label class="form-label">Domain *</label>
            <select name="domain_id" class="form-control" required>
                @foreach($domains as $domain)
                <option value="{{ $domain->id }}">{{ $domain->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    
    <div class="form-group">
        <label class="form-label">Name *</label>
        <input type="text" name="name" class="form-control" required>
    </div>
    
    <div class="form-group">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="3"></textarea>
    </div>
    
    <div class="form-row">
        <div class="form-group">
            <label class="form-label">Weight (1-10)</label>
            <input type="number" name="weight" class="form-control" value="1" min="1" max="10">
        </div>
        
        <div class="form-group">
            <label class="form-label">Max Score</label>
            <input type="number" name="max_score" class="form-control" value="5" min="1" max="10">
        </div>
        
        <div class="form-group">
            <label class="form-label">Passing Score</label>
            <input type="number" name="passing_score" class="form-control" value="3" min="0" max="10">
        </div>
    </div>
    
    <div class="form-group">
        <label class="checkbox-label">
            <input type="checkbox" name="evidence_required" value="1">
            Evidence Required
        </label>
    </div>
    
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Create Criteria</button>
        <a href="{{ route('admin.criteria.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
</form>
@endsection