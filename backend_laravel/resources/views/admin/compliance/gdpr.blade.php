@extends('admin.layouts.app')

@section('title', 'GDPR Compliance')
@section('page-title', 'GDPR Compliance Dashboard')

@section('content')
<div class="gdpr-overview">
    <div class="stat-card">
        <div class="stat-value">{{ $dataSubjects }}</div>
        <div class="stat-label">Data Subjects</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $processingActivities }}</div>
        <div class="stat-label">Processing Activities</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $dsarRequests }}</div>
        <div class="stat-label">DSAR Requests</div>
    </div>
</div>

<div class="rights-status">
    <h3>Data Subject Rights Status</h3>
    <table class="data-table">
        <thead>
            <tr><th>Right</th><th>Status</th><th>Last Exercised</th><th>SLA Compliance</th></tr>
        </thead>
        <tbody>
            <tr><td>Right to Access</td><td><span class="badge badge-success">Compliant</span></td><td>2024-01-15</td><td>98%</td></tr>
            <tr><td>Right to Rectification</td><td><span class="badge badge-success">Compliant</span></td><td>2024-01-20</td><td>100%</td></tr>
            <tr><td>Right to Erasure</td><td><span class="badge badge-warning">Partial</span></td><td>2024-01-10</td><td>85%</td></tr>
            <tr><td>Right to Portability</td><td><span class="badge badge-success">Compliant</span></td><td>2024-01-25</td><td>95%</td></tr>
            <tr><td>Right to Object</td><td><span class="badge badge-success">Compliant</span></td><td>2024-01-18</td><td>97%</td></tr>
        </tbody>
    </table>
</div>

<div class="data-mapping">
    <h3>Data Flow Mapping</h3>
    <div id="dataFlowChart"></div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/echarts@5/dist/echarts.min.js"></script>
<script>
    var chart = echarts.init(document.getElementById('dataFlowChart'));
    chart.setOption({
        series: [{
            type: 'graph',
            layout: 'force',
            data: {!! json_encode($nodes) !!},
            links: {!! json_encode($links) !!},
            roam: true,
            label: { show: true, position: 'right' },
            lineStyle: { color: 'source', curveness: 0.3 }
        }]
    });
</script>
@endpush