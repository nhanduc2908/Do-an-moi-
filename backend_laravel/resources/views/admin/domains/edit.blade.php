@extends('admin.layouts.app')

@section('title', 'Edit Domain')
@section('page-title', 'Edit Domain: {{ $domain->name }}')

@section('content')
<form method="POST" action="{{ route('admin.domains.update', $domain) }}">
    @csrf @method('PUT')
    
    <div class="form-group">
        <label class="form-label">Domain Code</label>
        <input type="text" class="form-control" value="{{ $domain->code }}" readonly disabled>
    </div>
    
    <div class="form-group">
        <label class="form-label">Domain Name *</label>
        <input type="text" name="name" class="form-control" value="{{ $domain->name }}" required>
    </div>
    
    <div class="form-group">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="3">{{ $domain->description }}</textarea>
    </div>
    
    <div class="form-group">
        <label class="form-label">Weight (%)</label>
        <input type="number" name="weight" class="form-control" value="{{ $domain->weight }}" min="0" max="100">
    </div>
    
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Update Domain</button>
        <a href="{{ route('admin.domains.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
</form>
@endsection