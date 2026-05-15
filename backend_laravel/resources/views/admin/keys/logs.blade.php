@extends('admin.layouts.app')

@section('title', 'Key Usage Logs')
@section('page-title', 'Encryption Key Usage Logs')

@section('content')
<div class="filter-bar">
    <select id="keyFilter" class="form-control">
        <option value="">All Keys</option>
        @foreach($keys as $key)
        <option value="{{ $key->id }}">{{ $key->key_id }}</option>
        @endforeach
    </select>
    
    <select id="actionFilter" class="form-control">
        <option value="">All Actions</option>
        <option value="encrypt">Encrypt</option>
        <option value="decrypt">Decrypt</option>
        <option value="sign">Sign</option>
        <option value="verify">Verify</option>
    </select>
    
    <input type="date" id="dateFrom" class="form-control" placeholder="From">
    <input type="date" id="dateTo" class="form-control" placeholder="To">
    <button id="filterBtn" class="btn btn-primary">Filter</button>
</div>

<div class="logs-list">
    <table class="data-table">
        <thead>
            <tr><th>Time</th><th>Key ID</th><th>Action</th><th>User</th><th>IP Address</th><th>Details</th></tr>
        </thead>
        <tbody id="logsTableBody">
            @include('admin.keys.partials.logs_rows', ['logs' => $logs])
        </tbody>
    </table>
    <div id="pagination">{{ $logs->links() }}</div>
</div>

@push('scripts')
<script>
    document.getElementById('filterBtn').addEventListener('click', async function() {
        const params = new URLSearchParams({
            key_id: document.getElementById('keyFilter').value,
            action: document.getElementById('actionFilter').value,
            from: document.getElementById('dateFrom').value,
            to: document.getElementById('dateTo').value
        });
        
        const response = await fetch(`/admin/keys/logs/filter?${params}`);
        const html = await response.text();
        document.getElementById('logsTableBody').innerHTML = html;
    });
</script>
@endpush
@endsection