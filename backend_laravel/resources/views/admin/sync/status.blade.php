@extends('admin.layouts.app')

@section('title', 'Sync Status')
@section('page-title', 'Data Synchronization Status')

@section('content')
<div class="sync-overview">
    <div class="stat-card">
        <div class="stat-value">{{ $totalSyncs }}</div>
        <div class="stat-label">Total Sync Operations</div>
    </div>
    <div class="stat-card success">
        <div class="stat-value">{{ $successfulSyncs }}</div>
        <div class="stat-label">Successful</div>
    </div>
    <div class="stat-card danger">
        <div class="stat-value">{{ $failedSyncs }}</div>
        <div class="stat-label">Failed</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $pendingSyncs }}</div>
        <div class="stat-label">Pending</div>
    </div>
</div>

<div class="sync-targets">
    <div class="sync-card">
        <div class="sync-header">
            <h3>Flutter Mobile App</h3>
            <span class="sync-status {{ $flutterStatus ? 'connected' : 'disconnected' }}">
                {{ $flutterStatus ? 'Connected' : 'Disconnected' }}
            </span>
        </div>
        <div class="sync-details">
            <div>Last Sync: {{ $flutterLastSync ?? 'Never' }}</div>
            <div>Items Synced: {{ $flutterItemsSynced }}</div>
        </div>
        <button class="btn btn-primary sync-now" data-target="flutter">Sync Now</button>
    </div>
    
    <div class="sync-card">
        <div class="sync-header">
            <h3>Firebase</h3>
            <span class="sync-status {{ $firebaseStatus ? 'connected' : 'disconnected' }}">
                {{ $firebaseStatus ? 'Connected' : 'Disconnected' }}
            </span>
        </div>
        <div class="sync-details">
            <div>Last Sync: {{ $firebaseLastSync ?? 'Never' }}</div>
            <div>Collections: {{ $firebaseCollections }}</div>
        </div>
        <button class="btn btn-primary sync-now" data-target="firebase">Sync Now</button>
    </div>
    
    <div class="sync-card">
        <div class="sync-header">
            <h3>WebSocket</h3>
            <span class="sync-status {{ $websocketStatus ? 'connected' : 'disconnected' }}">
                {{ $websocketStatus ? 'Connected' : 'Disconnected' }}
            </span>
        </div>
        <div class="sync-details">
            <div>Active Connections: {{ $activeConnections }}</div>
            <div>Messages/sec: {{ $messagesPerSec }}</div>
        </div>
        <button class="btn btn-primary sync-now" data-target="websocket">Reconnect</button>
    </div>
</div>

<div class="widget">
    <div class="widget-title">Recent Sync Logs</div>
    <table class="data-table">
        <thead>
            <tr><th>Time</th><th>Type</th><th>Status</th><th>Items</th><th>Duration</th><th>Error</th></tr>
        </thead>
        <tbody>
            @foreach($recentLogs as $log)
            <tr>
                <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                <td>{{ ucfirst($log->sync_type) }}</td>
                <td><span class="badge badge-{{ $log->status === 'success' ? 'success' : 'danger' }}">
                    {{ $log->status }}
                </span></td>
                <td>{{ $log->items_synced }}</td>
                <td>{{ $log->duration ?? '-' }}s</td>
                <td>{{ Str::limit($log->error_message, 50) ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@push('scripts')
<script>
    document.querySelectorAll('.sync-now').forEach(btn => {
        btn.addEventListener('click', async function() {
            const target = this.dataset.target;
            this.disabled = true;
            this.textContent = 'Syncing...';
            
            const response = await fetch(`/admin/sync/${target}/trigger`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            });
            
            if (response.ok) {
                alert(`${target} sync triggered successfully`);
                setTimeout(() => location.reload(), 2000);
            } else {
                alert(`Sync failed for ${target}`);
            }
            
            this.disabled = false;
            this.textContent = 'Sync Now';
        });
    });
</script>
@endpush
@endsection