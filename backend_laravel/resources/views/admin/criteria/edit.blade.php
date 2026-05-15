@extends('admin.layouts.app')

@section('title', 'Edit Criteria')
@section('page-title', 'Edit Criteria: {{ $criterion->code }}')

@section('content')
<form method="POST" action="{{ route('admin.criteria.update', $criterion) }}">
    @csrf @method('PUT')
    
    <div class="form-group">
        <label class="form-label">Code</label>
        <input type="text" class="form-control" value="{{ $criterion->code }}" readonly disabled>
    </div>
    
    <div class="form-group">
        <label class="form-label">Domain</label>
        <select name="domain_id" class="form-control">
            @foreach($domains as $domain)
            <option value="{{ $domain->id }}" {{ $criterion->domain_id == $domain->id ? 'selected' : '' }}>
                {{ $domain->name }}
            </option>
            @endforeach
        </select>
    </div>
    
    <div class="form-group">
        <label class="form-label">Name</label>
        <input type="text" name="name" class="form-control" value="{{ $criterion->name }}" required>
    </div>
    
    <div class="form-group">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="3">{{ $criterion->description }}</textarea>
    </div>
    
    <div class="form-row">
        <div class="form-group">
            <label class="form-label">Weight</label>
            <input type="number" name="weight" class="form-control" value="{{ $criterion->weight }}" min="1" max="10">
        </div>
        
        <div class="form-group">
            <label class="form-label">Passing Score</label>
            <input type="number" name="passing_score" class="form-control" value="{{ $criterion->passing_score }}" min="0" max="{{ $criterion->max_score }}">
        </div>
    </div>
    
    <div class="form-group">
        <label class="checkbox-label">
            <input type="checkbox" name="evidence_required" value="1" {{ $criterion->evidence_required ? 'checked' : '' }}>
            Evidence Required
        </label>
    </div>
    
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Update Criteria</button>
        <a href="{{ route('admin.criteria.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
</form>
@endsection