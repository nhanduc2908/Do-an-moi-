@extends('admin.layouts.app')

@section('title', 'Role Management')
@section('page-title', 'Manage Roles')

@section('content')
<div class="toolbar">
    <button class="btn btn-primary" onclick="showCreateRoleModal()">+ Add Role</button>
</div>

<table class="data-table">
    <thead><tr><th>Name</th><th>Display Name</th><th>Level</th><th>Users</th><th>Actions</th></tr></thead>
    <tbody>
        @foreach($roles as $role)
        <tr>
            <td>{{ $role->name }}</td>
            <td>{{ $role->display_name }}</td>
            <td>{{ $role->level }}</td>
            <td>{{ $role->users_count }}</td>
            <td><button class="btn btn-sm" onclick="editRole('{{ $role->id }}')">Edit</button></td>
        </tr>
        @endforeach
    </tbody>
</table>

@push('scripts')
<script>
    function showCreateRoleModal() { alert('Create role form'); }
    function editRole(id) { alert('Edit role: ' + id); }
</script>
@endpush
@endsection