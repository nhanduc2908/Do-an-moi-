@extends('admin.layouts.app')

@section('title', 'Edit Role')
@section('page-title', 'Edit Role: {{ $role->display_name }}')

@section('content')
<form method="POST" action="{{ route('admin.roles.update', $role) }}">
    @csrf @method('PUT')
    
    <div class="form-group">
        <label class="form-label">Role Name</label>
        <input type="text" name="name" class="form-control" value="{{ $role->name }}" readonly disabled>
    </div>
    
    <div class="form-group">
        <label class="form-label">Display Name</label>
        <input type="text" name="display_name" class="form-control" value="{{ $role->display_name }}" required>
    </div>
    
    <div class="form-group">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="3">{{ $role->description }}</textarea>
    </div>
    
    <div class="form-group">
        <label class="form-label">Level</label>
        <input type="number" name="level" class="form-control" value="{{ $role->level }}" min="1" max="100" {{ $role->is_system_role ? 'readonly' : '' }}>
    </div>
    
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Update Role</button>
        <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
</form>
@endsection