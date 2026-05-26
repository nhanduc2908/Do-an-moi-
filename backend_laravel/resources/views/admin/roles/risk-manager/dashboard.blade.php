@extends('admin.layouts.app')

@section('title', 'Risk Dashboard')
@section('page-title', 'Risk Management')

@section('content')
<div class="stats-grid">
    <div class="stat-card"><div class="stat-value">{{ $highRisks ?? 0 }}</div><div class="stat-label">High Risks</div></div>
    <div class="stat-card"><div class="stat-value">{{ $mediumRisks ?? 0 }}</div><div class="stat-label">Medium Risks</div></div>
    <div class="stat-card"><div class="stat-value">{{ $lowRisks ?? 0 }}</div><div class="stat-label">Low Risks</div></div>
</div>

<div class="action-buttons">
    <a href="{{ route('risk-manager.register') }}" class="btn btn-primary">📋 Risk Register</a>
    <a href="{{ route('risk-manager.assessment') }}" class="btn btn-secondary">📊 Risk Assessment</a>
    <a href="{{ route('risk-manager.treatment') }}" class="btn btn-info">🛠️ Risk Treatment</a>
</div>
@endsection