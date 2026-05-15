@extends('admin.layouts.app')

@section('title', 'Roles Management')
@section('page-title', 'Roles')

@section('content')
<div class="toolbar">
    <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">+ Create Role</a>
</div>

<div class="roles-list">
    <table class="data-table">
        <thead>
            <tr><th>Name</th><th>Display Name</th><th>Level</th><th>Users</th><th>Actions</th></tr>
        </thead>
        <tbody>
            @foreach($roles as $role)
            <tr>
                <td>{{ $role->name }}</td>
                <td>{{ $role->display_name }}</td>
                <td>{{ $role->level }}</td>
                <td>{{ $role->users_count }}</td>
                <td class="action-buttons">
                    <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-sm">Edit</a>
                    <a href="{{ route('admin.roles.permissions', $role) }}" class="btn btn-sm">Permissions</a>
                    @if(!$role->is_system_role)
                    <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this role?')">Delete</button>
                    </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection