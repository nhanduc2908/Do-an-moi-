@extends('admin.layouts.app')

@section('title', 'Create Role')
@section('page-title', 'Create New Role')

@section('content')
<form method="POST" action="{{ route('admin.roles.store') }}">
    @csrf
    
    <div class="form-group">
        <label class="form-label">Role Name (system name)</label>
        <input type="text" name="name" class="form-control" required pattern="[a-z_]+">
        <small>Use lowercase letters and underscores only</small>
    </div>
    
    <div class="form-group">
        <label class="form-label">Display Name</label>
        <input type="text" name="display_name" class="form-control" required>
    </div>
    
    <div class="form-group">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="3"></textarea>
    </div>
    
    <div class="form-group">
        <label class="form-label">Level (1-100, higher = more privileged)</label>
        <input type="number" name="level" class="form-control" value="10" min="1" max="100">
    </div>
    
    <div class="form-group">
        <label class="form-label">Permissions</label>
        <div class="permissions-grid">
            @foreach($permissions as $module => $perms)
            <div class="permission-group">
                <h4>{{ ucfirst($module) }}</h4>
                @foreach($perms as $perm)
                <label class="checkbox-label">
                    <input type="checkbox" name="permissions[]" value="{{ $perm->name }}">
                    {{ $perm->display_name }}
                </label>
                @endforeach
            </div>
            @endforeach
        </div>
    </div>
    
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Create Role</button>
        <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
</form>
@endsection