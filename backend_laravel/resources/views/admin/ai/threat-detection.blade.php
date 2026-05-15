@extends('admin.layouts.app')

@section('title', 'AI Threat Detection')
@section('page-title', 'Real-time Threat Detection')

@section('content')
<div class="detection-controls">
    <div class="live-status">
        <span class="status-dot live"></span>
        <span>Live Monitoring Active</span>
    </div>
    <button id="refreshBtn" class="btn btn-secondary">Refresh</button>
</div>

<div class="detection-grid">
    <div class="widget">
        <div class="widget-title">Real-time Threats</div>
        <div id="liveThreats" class="threat-list">
            @foreach($liveThreats as $threat)
            <div class="threat-item severity-{{ strtolower($threat->severity) }}">
                <div class="threat-info">
                    <span class="threat-type">{{ $threat->threat_type }}</span>
                    <span class="threat-time">{{ $threat->detected_at->diffForHumans() }}</span>
                </div>
                <div class="threat-details">
                    Source: {{ $threat->source_ip }}
                </div>
            </div>
            @endforeach
        </div>
    </div>
    
    <div class="widget">
        <div class="widget-title">Threat Intelligence Feed</div>
        <div id="threatIntel" class="intel-list">
            @foreach($threatIntel as $intel)
            <div class="intel-item">
                <div class="intel-header">
                    <span class="intel-cve">{{ $intel['cve_id'] }}</span>
                    <span class="intel-score severity-{{ strtolower($intel['severity']) }}">{{ $intel['severity'] }}</span>
                </div>
                <div class="intel-title">{{ $intel['title'] }}</div>
                <div class="intel-date">Published: {{ $intel['published_at'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<div class="widget">
    <div class="widget-title">Detection Rules (ML Models)</div>
    <table class="data-table">
        <thead>
            <tr><th>Model Name</th><th>Version</th><th>Accuracy</th><th>False Positive Rate</th><th>Status</th></tr>
        </thead>
        <tbody>
            <tr><td>Anomaly Detection v2</td><td>2.1.0</td><td>94.5%</td><td>2.3%</td><td><span class="badge badge-success">Active</span></td></tr>
            <tr><td>Malware Classifier</td><td>3.0.2</td><td>96.2%</td><td>1.8%</td><td><span class="badge badge-success">Active</span></td></tr>
            <tr><td>Phishing Detector</td><td>1.5.3</td><td>91.8%</td><td>3.1%</td><td><span class="badge badge-success">Active</span></td></tr>
            <tr><td>User Behavior Analysis</td><td>2.0.1</td><td>88.5%</td><td>4.2%</td><td><span class="badge badge-warning">Training</span></td></tr>
        </tbody>
    </table>
</div>

@push('scripts')
<script>
    let eventSource;
    
    function startLiveMonitoring() {
        eventSource = new EventSource('{{ route("admin.ai.threats.stream") }}');
        
        eventSource.onmessage = function(event) {
            const threat = JSON.parse(event.data);
            addThreatToList(threat);
        };
        
        eventSource.onerror = function() {
            setTimeout(startLiveMonitoring, 5000);
        };
    }
    
    function addThreatToList(threat) {
        const container = document.getElementById('liveThreats');
        const threatElement = document.createElement('div');
        threatElement.className = `threat-item severity-${threat.severity.toLowerCase()}`;
        threatElement.innerHTML = `
            <div class="threat-info">
                <span class="threat-type">${threat.threat_type}</span>
                <span class="threat-time">just now</span>
            </div>
            <div class="threat-details">
                Source: ${threat.source_ip}
            </div>
        `;
        container.insertBefore(threatElement, container.firstChild);
        
        if (container.children.length > 50) {
            container.removeChild(container.lastChild);
        }
    }
    
    startLiveMonitoring();
    
    document.getElementById('refreshBtn').addEventListener('click', function() {
        location.reload();
    });
</script>
@endpush
@endsection