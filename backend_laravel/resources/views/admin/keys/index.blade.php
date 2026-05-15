@extends('admin.layouts.app')

@section('title', 'Encryption Keys')
@section('page-title', 'Encryption Key Management')

@section('content')
<div class="toolbar">
    <a href="{{ route('admin.keys.generate') }}" class="btn btn-primary">+ Generate New Key</a>
    <a href="{{ route('admin.keys.logs') }}" class="btn btn-secondary">View Logs</a>
</div>

<div class="keys-list">
    <table class="data-table">
        <thead>
            <tr><th>Key ID</th><th>Type</th><th>Size</th><th>Purpose</th><th>Status</th><th>Expires</th><th>Actions</th></tr>
        </thead>
        <tbody>
            @foreach($keys as $key)
            <tr>
                <td>{{ $key->key_id }}</td>
                <td>{{ $key->type }}</td>
                <td>{{ $key->size }}</td>
                <td>{{ $key->purpose }}</td>
                <td><span class="badge badge-{{ $key->status }}">{{ $key->status }}</span></td>
                <td>{{ $key->expires_at ? $key->expires_at->format('Y-m-d') : 'Never' }}</td>
                <td class="action-buttons">
                    <button class="btn btn-sm" onclick="viewKeyDetails('{{ $key->id }}')">Details</button>
                    @if($key->status === 'active')
                    <form method="POST" action="{{ route('admin.keys.revoke', $key) }}" class="inline">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Revoke this key?')">Revoke</button>
                    </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $keys->links() }}
</div>

<!-- Key Details Modal -->
<div id="keyModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Key Details</h3>
            <button class="close" onclick="closeModal()">&times;</button>
        </div>
        <div class="modal-body" id="keyDetailsContent">
            <div class="loading">Loading...</div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    async function viewKeyDetails(keyId) {
        const modal = document.getElementById('keyModal');
        const content = document.getElementById('keyDetailsContent');
        modal.style.display = 'block';
        content.innerHTML = '<div class="loading">Loading...</div>';
        
        try {
            const response = await fetch(`/admin/keys/${keyId}/details`);
            const data = await response.json();
            content.innerHTML = `
                <table class="info-table">
                    <tr><th>Key ID:</th><td>${data.key_id}</td></tr>
                    <tr><th>Fingerprint:</th><td><code>${data.fingerprint}</code></td></tr>
                    <tr><th>Type:</th><td>${data.type}</td></tr>
                    <tr><th>Size:</th><td>${data.size} bits</td></tr>
                    <tr><th>Created:</th><td>${data.created_at}</td></tr>
                    <tr><th>Created By:</th><td>${data.created_by || 'System'}</td></tr>
                </table>
            `;
        } catch (error) {
            content.innerHTML = '<div class="error">Failed to load key details</div>';
        }
    }
    
    function closeModal() {
        document.getElementById('keyModal').style.display = 'none';
    }
</script>
@endpush
@endsection