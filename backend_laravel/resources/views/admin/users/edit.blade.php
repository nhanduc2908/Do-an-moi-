@extends('admin.layouts.app')

@section('title', 'Edit User')
@section('page-title', 'Edit User: {{ $user->name }}')

@section('content')
<form method="POST" action="{{ route('admin.users.update', $user) }}">
    @csrf @method('PUT')
    
    <div class="form-group">
        <label class="form-label">Full Name</label>
        <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
    </div>
    
    <div class="form-group">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
    </div>
    
    <div class="form-group">
        <label class="form-label">New Password (leave blank to keep current)</label>
        <input type="password" name="password" class="form-control">
    </div>
    
    <div class="form-group">
        <label class="form-label">Department</label>
        <select name="department" class="form-control">
            <option value="">Select Department</option>
            <option value="IT" {{ $user->department == 'IT' ? 'selected' : '' }}>IT</option>
            <option value="Security" {{ $user->department == 'Security' ? 'selected' : '' }}>Security</option>
            <option value="Compliance" {{ $user->department == 'Compliance' ? 'selected' : '' }}>Compliance</option>
        </select>
    </div>
    
    <div class="form-group">
        <label class="form-label">Status</label>
        <select name="status" class="form-control">
            <option value="active" {{ $user->status == 'active' ? 'selected' : '' }}>Active</option>
            <option value="suspended" {{ $user->status == 'suspended' ? 'selected' : '' }}>Suspended</option>
            <option value="inactive" {{ $user->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>
    
    <div class="form-group">
        <label class="form-label">Role</label>
        <select name="role" class="form-control">
            @foreach($roles as $role)
            <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                {{ $role->display_name }}
            </option>
            @endforeach
        </select>
    </div>
    
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Update User</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
</form>
@endsection