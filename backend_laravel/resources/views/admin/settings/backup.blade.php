@extends('admin.layouts.app')

@section('title', 'Backup Settings')
@section('page-title', 'Backup & Recovery Configuration')

@section('content')
<form method="POST" action="{{ route('admin.settings.backup.update') }}">
    @csrf @method('PUT')
    
    <div class="settings-section">
        <h3>Backup Schedule</h3>
        
        <div class="form-group">
            <label class="checkbox-label">
                <input type="checkbox" name="backup_enabled" value="1" {{ setting('backup_enabled', true) ? 'checked' : '' }}>
                Enable Automatic Backups
            </label>
        </div>
        
        <div class="form-group">
            <label class="form-label">Backup Frequency</label>
            <select name="backup_frequency" class="form-control">
                <option value="daily" {{ setting('backup_frequency') == 'daily' ? 'selected' : '' }}>Daily</option>
                <option value="weekly" {{ setting('backup_frequency') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                <option value="monthly" {{ setting('backup_frequency') == 'monthly' ? 'selected' : '' }}>Monthly</option>
            </select>
        </div>
        
        <div class="form-group">
            <label class="form-label">Backup Time (UTC)</label>
            <input type="time" name="backup_time" class="form-control" value="{{ setting('backup_time', '02:00') }}">
        </div>
        
        <div class="form-group">
            <label class="form-label">Retention Days</label>
            <input type="number" name="backup_retention_days" class="form-control" value="{{ setting('backup_retention_days', 30) }}" min="1" max="365">
        </div>
    </div>
    
    <div class="settings-section">
        <h3>Backup Destination</h3>
        
        <div class="form-group">
            <label class="form-label">Storage Driver</label>
            <select name="backup_driver" class="form-control">
                <option value="local" {{ setting('backup_driver') == 'local' ? 'selected' : '' }}>Local Storage</option>
                <option value="s3" {{ setting('backup_driver') == 's3' ? 'selected' : '' }}>Amazon S3</option>
            </select>
        </div>
        
        <div class="form-group s3-fields" style="display: none;">
            <label class="form-label">S3 Bucket</label>
            <input type="text" name="s3_bucket" class="form-control" value="{{ setting('s3_bucket') }}">
        </div>
        
        <div class="form-group s3-fields" style="display: none;">
            <label class="form-label">S3 Region</label>
            <input type="text" name="s3_region" class="form-control" value="{{ setting('s3_region', 'us-east-1') }}">
        </div>
        
        <div class="form-group s3-fields" style="display: none;">
            <label class="form-label">S3 Access Key</label>
            <input type="text" name="s3_access_key" class="form-control" value="{{ setting('s3_access_key') }}">
        </div>
        
        <div class="form-group s3-fields" style="display: none;">
            <label class="form-label">S3 Secret Key</label>
            <input type="password" name="s3_secret_key" class="form-control">
        </div>
    </div>
    
    <div class="settings-section">
        <h3>Backup Encryption</h3>
        
        <div class="form-group">
            <label class="checkbox-label">
                <input type="checkbox" name="backup_encryption" value="1" {{ setting('backup_encryption', true) ? 'checked' : '' }}>
                Encrypt Backups
            </label>
        </div>
        
        <div class="form-group">
            <label class="form-label">Encryption Algorithm</label>
            <select name="backup_algorithm" class="form-control">
                <option value="aes-256-cbc" {{ setting('backup_algorithm') == 'aes-256-cbc' ? 'selected' : '' }}>AES-256-CBC</option>
                <option value="aes-256-gcm" {{ setting('backup_algorithm') == 'aes-256-gcm' ? 'selected' : '' }}>AES-256-GCM</option>
            </select>
        </div>
    </div>
    
    <div class="settings-section">
        <h3>Backup Contents</h3>
        
        <div class="form-group">
            <label class="checkbox-label">
                <input type="checkbox" name="backup_database" value="1" {{ setting('backup_database', true) ? 'checked' : '' }}>
                Database
            </label>
        </div>
        
        <div class="form-group">
            <label class="checkbox-label">
                <input type="checkbox" name="backup_files" value="1" {{ setting('backup_files', true) ? 'checked' : '' }}>
                Application Files
            </label>
        </div>
        
        <div class="form-group">
            <label class="checkbox-label">
                <input type="checkbox" name="backup_encrypted_files" value="1" {{ setting('backup_encrypted_files', true) ? 'checked' : '' }}>
                Encrypted Files
            </label>
        </div>
    </div>
    
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Save Backup Settings</button>
        <button type="button" id="runBackupBtn" class="btn btn-warning">Run Backup Now</button>
    </div>
</form>

@push('scripts')
<script>
    function toggleS3Fields() {
        const driver = document.querySelector('select[name="backup_driver"]').value;
        const s3Fields = document.querySelectorAll('.s3-fields');
        s3Fields.forEach(field => {
            field.style.display = driver === 's3' ? 'block' : 'none';
        });
    }
    
    document.querySelector('select[name="backup_driver"]').addEventListener('change', toggleS3Fields);
    toggleS3Fields();
    
    document.getElementById('runBackupBtn').addEventListener('click', async function() {
        this.disabled = true;
        this.textContent = 'Running...';
        
        const response = await fetch('{{ route("admin.settings.backup.run") }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        });
        
        if (response.ok) {
            alert('Backup completed successfully!');
        } else {
            alert('Backup failed. Check logs for details.');
        }
        
        this.disabled = false;
        this.textContent = 'Run Backup Now';
    });
</script>
@endpush
@endsection