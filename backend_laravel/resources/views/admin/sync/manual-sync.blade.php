@extends('admin.layouts.app')

@section('title', 'Manual Sync')
@section('page-title', 'Manual Data Synchronization')

@section('content')
<div class="manual-sync">
    <form id="syncForm">
        @csrf
        
        <div class="form-group">
            <label class="form-label">Sync Type</label>
            <select name="sync_type" class="form-control" required>
                <option value="full">Full Sync (All Data)</option>
                <option value="incremental">Incremental Sync</option>
                <option value="users">Users Only</option>
                <option value="assessments">Assessments Only</option>
                <option value="incidents">Incidents Only</option>
                <option value="vulnerabilities">Vulnerabilities Only</option>
            </select>
        </div>
        
        <div class="form-group">
            <label class="form-label">Target System</label>
            <select name="target" class="form-control" required>
                <option value="flutter">Flutter Mobile App</option>
                <option value="firebase">Firebase</option>
                <option value="both">Both (Flutter + Firebase)</option>
            </select>
        </div>
        
        <div class="form-group">
            <label class="form-label">Date Range (for incremental sync)</label>
            <div class="date-range">
                <input type="date" name="date_from" class="form-control">
                <span>to</span>
                <input type="date" name="date_to" class="form-control">
            </div>
        </div>
        
        <div class="form-group">
            <label class="checkbox-label">
                <input type="checkbox" name="force" value="1">
                Force sync (overwrite existing data)
            </label>
        </div>
        
        <div class="alert alert-warning">
            <strong>⚠️ Warning:</strong> Manual sync may take several minutes for large datasets. Do not close this page.
        </div>
        
        <button type="submit" class="btn btn-primary">Start Sync</button>
    </form>
    
    <div id="syncProgress" class="sync-progress" style="display: none;">
        <h4>Sync in Progress...</h4>
        <div class="progress-bar">
            <div id="syncProgressBar" class="progress-fill" style="width: 0%"></div>
        </div>
        <p id="progressMessage">Initializing...</p>
        <div id="syncResults" class="sync-results"></div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('syncForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const progressDiv = document.getElementById('syncProgress');
        const progressBar = document.getElementById('syncProgressBar');
        const progressMsg = document.getElementById('progressMessage');
        
        progressDiv.style.display = 'block';
        
        const response = await fetch('{{ route("admin.sync.manual.execute") }}', {
            method: 'POST',
            body: formData,
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        });
        
        const reader = response.body.getReader();
        const decoder = new TextDecoder();
        
        while (true) {
            const { done, value } = await reader.read();
            if (done) break;
            
            const chunk = decoder.decode(value);
            const lines = chunk.split('\n');
            
            for (const line of lines) {
                if (line.startsWith('data: ')) {
                    const data = JSON.parse(line.slice(6));
                    
                    if (data.type === 'progress') {
                        progressBar.style.width = data.percentage + '%';
                        progressMsg.textContent = data.message;
                    } else if (data.type === 'complete') {
                        progressMsg.textContent = 'Sync completed successfully!';
                        document.getElementById('syncResults').innerHTML = `
                            <div class="alert alert-success">
                                <strong>Sync Summary:</strong><br>
                                Items synced: ${data.items_synced}<br>
                                Duration: ${data.duration} seconds<br>
                                Errors: ${data.errors}
                            </div>
                        `;
                        setTimeout(() => {
                            window.location.href = '{{ route("admin.sync.status") }}';
                        }, 3000);
                    } else if (data.type === 'error') {
                        progressMsg.textContent = 'Sync failed: ' + data.message;
                    }
                }
            }
        }
    });
</script>
@endpush
@endsection