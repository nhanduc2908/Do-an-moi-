@extends('admin.layouts.app')

@section('title', 'Role Permissions')
@section('page-title', 'Permissions for: {{ $role->display_name }}')

@section('content')
<form method="POST" action="{{ route('admin.roles.permissions.update', $role) }}">
    @csrf @method('PUT')
    
    <div class="permissions-editor">
        @foreach($permissions as $module => $perms)
        <div class="permission-module">
            <h3>{{ ucfirst($module) }}</h3>
            <div class="permission-list">
                @foreach($perms as $perm)
                <label class="checkbox-label">
                    <input type="checkbox" name="permissions[]" value="{{ $perm->name }}" 
                        {{ $role->hasPermissionTo($perm->name) ? 'checked' : '' }}>
                    {{ $perm->display_name }}
                    <span class="permission-name">({{ $perm->name }})</span>
                </label>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
    
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Save Permissions</button>
        <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">Back</a>
    </div>
</form>
@endsection