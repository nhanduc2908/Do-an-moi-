@extends('admin.layouts.app')

@section('title', 'Risk Management Dashboard')
@section('page-title', 'Risk Assessment & Management')

@section('content')
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-value">{{ $highRisks ?? 0 }}</div>
        <div class="stat-label">High Risks</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $mediumRisks ?? 0 }}</div>
        <div class="stat-label">Medium Risks</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $lowRisks ?? 0 }}</div>
        <div class="stat-label">Low Risks</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $riskTolerance ?? 65 }}%</div>
        <div class="stat-label">Risk Tolerance</div>
    </div>
</div>

<div class="admin-grid">
    <div class="widget">
        <div class="widget-title">Risk Matrix</div>
        <div id="riskMatrix" class="risk-matrix"></div>
    </div>
    
    <div class="widget">
        <div class="widget-title">Top Risks</div>
        <table class="data-table">
            <thead><tr><th>Risk</th><th>Likelihood</th><th>Impact</th><th>Score</th></tr></thead>
            <tbody>
                @foreach($topRisks ?? [] as $risk)
                <tr>
                    <td>{{ $risk['name'] ?? '-' }}</td>
                    <td>{{ $risk['likelihood'] ?? '-' }}</td>
                    <td>{{ $risk['impact'] ?? '-' }}</td>
                    <td class="risk-{{ strtolower($risk['level'] ?? 'low') }}">{{ $risk['score'] ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="widget">
        <div class="widget-title">Quick Actions</div>
        <div class="action-buttons">
            <a href="{{ route('admin.risk.assess') }}" class="btn btn-primary">📊 New Assessment</a>
            <a href="{{ route('admin.risk.reports') }}" class="btn btn-secondary">📄 Risk Report</a>
            <a href="{{ route('admin.risk.matrix') }}" class="btn btn-info">🎯 Risk Matrix</a>
        </div>
    </div>
</div>
@endsection