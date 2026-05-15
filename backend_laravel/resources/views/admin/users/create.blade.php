@extends('admin.layouts.app')

@section('title', 'Add User')
@section('page-title', 'Add New User')

@section('content')
<form method="POST" action="{{ route('admin.users.store') }}">
    @csrf
    
    <div class="form-row">
        <div class="form-group">
            <label class="form-label">Full Name *</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        
        <div class="form-group">
            <label class="form-label">Email *</label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
    
    <div class="form-row">
        <div class="form-group">
            <label class="form-label">Password *</label>
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        
        <div class="form-group">
            <label class="form-label">Confirm Password *</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>
    </div>
    
    <div class="form-row">
        <div class="form-group">
            <label class="form-label">Department</label>
            <select name="department" class="form-control">
                <option value="">Select Department</option>
                <option value="IT">IT</option>
                <option value="Security">Security</option>
                <option value="Compliance">Compliance</option>
                <option value="HR">HR</option>
                <option value="Finance">Finance</option>
            </select>
        </div>
        
        <div class="form-group">
            <label class="form-label">Role</label>
            <select name="role" class="form-control" required>
                @foreach($roles as $role)
                <option value="{{ $role->name }}">{{ $role->display_name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Create User</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
</form>
@endsection