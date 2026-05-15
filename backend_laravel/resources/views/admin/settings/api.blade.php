@extends('admin.layouts.app')

@section('title', 'API Settings')
@section('page-title', 'API Configuration')

@section('content')
<form method="POST" action="{{ route('admin.settings.api.update') }}">
    @csrf @method('PUT')
    
    <div class="settings-section">
        <h3>API Configuration</h3>
        
        <div class="form-group">
            <label class="checkbox-label">
                <input type="checkbox" name="api_enabled" value="1" {{ setting('api_enabled', true) ? 'checked' : '' }}>
                Enable REST API
            </label>
        </div>
        
        <div class="form-group">
            <label class="form-label">API Rate Limit (requests per minute)</label>
            <input type="number" name="api_rate_limit" class="form-control" value="{{ setting('api_rate_limit', 100) }}" min="10" max="1000">
        </div>
        
        <div class="form-group">
            <label class="form-label">API Version</label>
            <select name="api_version" class="form-control">
                <option value="v1" {{ setting('api_version') == 'v1' ? 'selected' : '' }}>v1 (Current)</option>
                <option value="v2" {{ setting('api_version') == 'v2' ? 'selected' : '' }}>v2 (Beta)</option>
            </select>
        </div>
    </div>
    
    <div class="settings-section">
        <h3>Authentication</h3>
        
        <div class="form-group">
            <label class="checkbox-label">
                <input type="checkbox" name="api_auth_required" value="1" {{ setting('api_auth_required', true) ? 'checked' : '' }}>
                Require API Key for All Endpoints
            </label>
        </div>
        
        <div class="form-group">
            <label class="checkbox-label">
                <input type="checkbox" name="api_jwt_enabled" value="1" {{ setting('api_jwt_enabled', true) ? 'checked' : '' }}>
                Enable JWT Authentication
            </label>
        </div>
        
        <div class="form-group">
            <label class="form-label">JWT Token Lifetime (hours)</label>
            <input type="number" name="jwt_ttl" class="form-control" value="{{ setting('jwt_ttl', 24) }}" min="1" max="720">
        </div>
    </div>
    
    <div class="settings-section">
        <h3>API Keys Management</h3>
        
        <div class="api-keys-list">
            <h4>Active API Keys</h4>
            <table class="data-table">
                <thead>
                    <tr><th>Name</th><th>Key ID</th><th>Created</th><th>Expires</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    @foreach($apiKeys as $key)
                    <tr>
                        <td>{{ $key->name }}</td>
                        <td><code>{{ substr($key->key, 0, 16) }}...</code></td>
                        <td>{{ $key->created_at->format('Y-m-d') }}</td>
                        <td>{{ $key->expires_at ? $key->expires_at->format('Y-m-d') : 'Never' }}</td>
                        <td>
                            <button class="btn btn-danger btn-sm" onclick="revokeKey('{{ $key->id }}')">Revoke</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="form-group">
            <label class="form-label">Generate New API Key</label>
            <div class="input-group">
                <input type="text" id="newKeyName" class="form-control" placeholder="Key Name">
                <button type="button" id="generateKeyBtn" class="btn btn-primary">Generate Key</button>
            </div>
        </div>
        
        <div id="newKeyDisplay" class="alert alert-success" style="display: none;">
            <strong>New API Key Generated:</strong>
            <code id="newKeyValue"></code>
            <p class="mt-2"><strong>Important:</strong> Copy this key now. It will not be shown again!</p>
            <button class="btn btn-sm" onclick="copyNewKey()">Copy to Clipboard</button>
        </div>
    </div>
    
    <div class="settings-section">
        <h3>CORS Settings</h3>
        
        <div class="form-group">
            <label class="form-label">Allowed Origins (comma separated)</label>
            <input type="text" name="cors_allowed_origins" class="form-control" value="{{ setting('cors_allowed_origins', '*') }}">
        </div>
        
        <div class="form-group">
            <label class="checkbox-label">
                <input type="checkbox" name="cors_allow_credentials" value="1" {{ setting('cors_allow_credentials', true) ? 'checked' : '' }}>
                Allow Credentials
            </label>
        </div>
    </div>
    
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Save API Settings</button>
    </div>
</form>

@push('scripts')
<script>
    document.getElementById('generateKeyBtn').addEventListener('click', async function() {
        const name = document.getElementById('newKeyName').value;
        if (!name) {
            alert('Please enter a key name');
            return;
        }
        
        const response = await fetch('{{ route("admin.settings.api.generate-key") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ name: name })
        });
        
        const data = await response.json();
        
        if (data.success) {
            document.getElementById('newKeyValue').textContent = data.api_key;
            document.getElementById('newKeyDisplay').style.display = 'block';
            setTimeout(() => location.reload(), 3000);
        } else {
            alert('Failed to generate key: ' + data.message);
        }
    });
    
    async function revokeKey(id) {
        if (confirm('Revoke this API key? This action cannot be undone.')) {
            const response = await fetch(`/admin/settings/api/revoke-key/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            });
            
            if (response.ok) {
                location.reload();
            } else {
                alert('Failed to revoke key');
            }
        }
    }
    
    function copyNewKey() {
        const key = document.getElementById('newKeyValue').textContent;
        navigator.clipboard.writeText(key);
        alert('Key copied to clipboard!');
    }
</script>
@endpush
@endsection