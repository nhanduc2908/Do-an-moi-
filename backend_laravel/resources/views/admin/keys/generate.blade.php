@extends('admin.layouts.app')

@section('title', 'Generate Key')
@section('page-title', 'Generate New Encryption Key')

@section('content')
<form method="POST" action="{{ route('admin.keys.store') }}" id="keyForm">
    @csrf
    
    <div class="form-row">
        <div class="form-group">
            <label class="form-label">Key Type *</label>
            <select name="type" class="form-control" required>
                <option value="">Select Type</option>
                <option value="RSA">RSA (Asymmetric)</option>
                <option value="AES">AES (Symmetric)</option>
                <option value="ECC">ECC (Elliptic Curve)</option>
            </select>
        </div>
        
        <div class="form-group">
            <label class="form-label">Key Size *</label>
            <select name="size" class="form-control" required>
                <option value="">Select Size</option>
                <option value="128">AES-128</option>
                <option value="256">AES-256 / ECC-256</option>
                <option value="2048">RSA-2048</option>
                <option value="4096">RSA-4096</option>
            </select>
        </div>
    </div>
    
    <div class="form-group">
        <label class="form-label">Purpose *</label>
        <select name="purpose" class="form-control" required>
            <option value="encryption">Data Encryption</option>
            <option value="authentication">Authentication</option>
            <option value="signing">Digital Signing</option>
            <option value="backup">Backup Encryption</option>
        </select>
    </div>
    
    <div class="form-group">
        <label class="form-label">Expiry (days)</label>
        <input type="number" name="expiry_days" class="form-control" value="365" min="1" max="3650">
        <small>Leave empty for no expiry</small>
    </div>
    
    <div class="form-group">
        <label class="form-label">Tags (comma separated)</label>
        <input type="text" name="tags" class="form-control" placeholder="production, database, backup">
    </div>
    
    <div class="alert alert-warning">
        <strong>⚠️ Important:</strong> The private key will only be shown once. Store it securely!
    </div>
    
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Generate Key</button>
        <a href="{{ route('admin.keys.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
</form>

<div id="keyResult" class="key-result" style="display: none;">
    <div class="alert alert-success">
        <h4>Key Generated Successfully!</h4>
        <p><strong>Key ID:</strong> <span id="keyId"></span></p>
        <p><strong>Fingerprint:</strong> <code id="fingerprint"></code></p>
    </div>
    <div class="private-key-section">
        <h4>Private Key (RSA/ECC only)</h4>
        <textarea id="privateKey" class="form-control" rows="10" readonly></textarea>
        <button class="btn btn-primary" onclick="copyPrivateKey()">Copy to Clipboard</button>
        <button class="btn btn-secondary" onclick="downloadKey()">Download</button>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('keyForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        const response = await fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        });
        
        const data = await response.json();
        
        if (data.success) {
            document.getElementById('keyId').textContent = data.key_id;
            document.getElementById('fingerprint').textContent = data.fingerprint;
            document.getElementById('privateKey').value = data.private_key || 'N/A (Symmetric key)';
            document.getElementById('keyResult').style.display = 'block';
            document.getElementById('keyForm').style.display = 'none';
        } else {
            alert('Failed to generate key: ' + data.message);
        }
    });
    
    function copyPrivateKey() {
        const textarea = document.getElementById('privateKey');
        textarea.select();
        document.execCommand('copy');
        alert('Private key copied to clipboard!');
    }
    
    function downloadKey() {
        const content = document.getElementById('privateKey').value;
        const blob = new Blob([content], { type: 'text/plain' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'private_key.txt';
        a.click();
        URL.revokeObjectURL(url);
    }
</script>
@endpush
@endsection