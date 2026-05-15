@extends('admin.layouts.app')

@section('title', 'Add Domain')
@section('page-title', 'Add New Security Domain')

@section('content')
<form method="POST" action="{{ route('admin.domains.store') }}">
    @csrf
    
    <div class="form-group">
        <label class="form-label">Domain Code *</label>
        <input type="text" name="code" class="form-control" required placeholder="e.g., AC, NS, CR">
        <small>2-5 character unique identifier</small>
    </div>
    
    <div class="form-group">
        <label class="form-label">Domain Name *</label>
        <input type="text" name="name" class="form-control" required>
    </div>
    
    <div class="form-group">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="3"></textarea>
    </div>
    
    <div class="form-group">
        <label class="form-label">Weight (%)</label>
        <input type="number" name="weight" class="form-control" value="10" min="0" max="100">
    </div>
    
    <div class="form-group">
        <label class="form-label">Parent Domain</label>
        <select name="parent_id" class="form-control">
            <option value="">None (Top Level)</option>
            @foreach($domains as $domain)
            <option value="{{ $domain->id }}">{{ $domain->name }}</option>
            @endforeach
        </select>
    </div>
    
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Create Domain</button>
        <a href="{{ route('admin.domains.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
</form>
@endsection