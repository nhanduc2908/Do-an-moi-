@extends('admin.layouts.app')

@section('title', 'User Management')
@section('page-title', 'Manage Users')

@section('content')
<div class="toolbar">
    <button class="btn btn-primary" onclick="showCreateUserModal()">+ Add User</button>
    <input type="text" id="searchInput" class="form-control search-input" placeholder="Search...">
</div>

<table class="data-table">
    <thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Status</th><th>Actions</th></tr></thead>
    <tbody>
        @foreach($users as $user)
        <tr>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->roles->first()->display_name ?? '-' }}</td>
            <td><span class="badge badge-{{ $user->status }}">{{ $user->status }}</span></td>
            <td><button class="btn btn-sm" onclick="editUser('{{ $user->id }}')">Edit</button></td>
        </tr>
        @endforeach
    </tbody>
</table>
{{ $users->links() }}

<div id="userModal" class="modal" style="display:none">
    <div class="modal-content">
        <form id="userForm">
            @csrf
            <input type="hidden" id="userId">
            <input type="text" name="name" placeholder="Name" class="form-control" required>
            <input type="email" name="email" placeholder="Email" class="form-control" required>
            <input type="password" name="password" placeholder="Password" class="form-control">
            <select name="role" class="form-control">@foreach($roles as $role)<option value="{{ $role->name }}">{{ $role->display_name }}</option>@endforeach</select>
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    async function editUser(id) {
        let res = await fetch(`/admin/users/${id}`);
        let data = await res.json();
        document.getElementById('userId').value = data.user.id;
        document.querySelector('[name="name"]').value = data.user.name;
        document.querySelector('[name="email"]').value = data.user.email;
        document.getElementById('userModal').style.display = 'block';
    }
    document.getElementById('userForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        let id = document.getElementById('userId').value;
        let url = id ? `/admin/users/${id}` : '/admin/users/create';
        let method = id ? 'PUT' : 'POST';
        await fetch(url, { method, body: new FormData(e.target), headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });
        location.reload();
    });
</script>
@endpush
@endsection