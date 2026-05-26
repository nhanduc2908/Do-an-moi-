@extends('admin.layouts.app')

@section('title', 'Compliance Dashboard')
@section('page-title', 'Compliance Management')

@section('content')
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-value">{{ $isoCompliance ?? 85 }}%</div>
        <div class="stat-label">ISO 27001</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $gdprCompliance ?? 78 }}%</div>
        <div class="stat-label">GDPR</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $pciCompliance ?? 72 }}%</div>
        <div class="stat-label">PCI DSS</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $openAuditFindings ?? 0 }}</div>
        <div class="stat-label">Open Findings</div>
    </div>
</div>

<div class="admin-grid">
    <div class="widget">
        <div class="widget-title">Compliance Status</div>
        <canvas id="complianceChart" height="250"></canvas>
    </div>
    
    <div class="widget">
        <div class="widget-title">Upcoming Audits</div>
        <table class="data-table">
            <thead><tr><th>Standard</th><th>Due Date</th><th>Status</th></tr></thead>
            <tbody>
                <tr><td>ISO 27001</td><td>2025-06-15</td><td><span class="badge badge-warning">Pending</span></td></tr>
                <tr><td>GDPR</td><td>2025-07-01</td><td><span class="badge badge-warning">Pending</span></td></tr>
                <tr><td>PCI DSS</td><td>2025-08-20</td><td><span class="badge badge-success">On Track</span></td></tr>
            </tbody>
        </table>
    </div>
    
    <div class="widget">
        <div class="widget-title">Quick Actions</div>
        <div class="action-buttons">
            <a href="{{ route('admin.compliance.iso27001') }}" class="btn btn-primary">📋 ISO 27001</a>
            <a href="{{ route('admin.compliance.gdpr') }}" class="btn btn-secondary">🇪🇺 GDPR</a>
            <a href="{{ route('admin.compliance.audit') }}" class="btn btn-info">🔍 Run Audit</a>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    new Chart(document.getElementById('complianceChart'), {
        type: 'bar',
        data: {
            labels: ['ISO 27001', 'GDPR', 'PCI DSS', 'NIST', 'HIPAA'],
            datasets: [{
                label: 'Compliance Score (%)',
                data: {!! json_encode($complianceScores ?? [85, 78, 72, 80, 65]) !!},
                backgroundColor: '#2ecc71'
            }]
        }
    });
</script>
@endpush
@endsection