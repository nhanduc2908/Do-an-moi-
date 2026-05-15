@extends('admin.layouts.app')

@section('title', 'AI Engine Dashboard')
@section('page-title', 'Artificial Intelligence Engine')

@section('content')
<div class="ai-stats">
    <div class="stat-card">
        <div class="stat-value">{{ $totalDetections }}</div>
        <div class="stat-label">Total Detections</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $threatsBlocked }}</div>
        <div class="stat-label">Threats Blocked</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ number_format($avgConfidence * 100, 1) }}%</div>
        <div class="stat-label">Avg Confidence</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $modelAccuracy }}%</div>
        <div class="stat-label">Model Accuracy</div>
    </div>
</div>

<div class="ai-grid">
    <div class="widget">
        <div class="widget-title">Threat Detection Trends</div>
        <canvas id="threatTrendChart" height="250"></canvas>
    </div>
    
    <div class="widget">
        <div class="widget-title">Anomaly Detection</div>
        <canvas id="anomalyChart" height="250"></canvas>
    </div>
</div>

<div class="widget">
    <div class="widget-title">Recent AI Predictions</div>
    <table class="data-table">
        <thead>
            <tr><th>Time</th><th>Type</th><th>Prediction</th><th>Confidence</th><th>Status</th></tr>
        </thead>
        <tbody>
            @foreach($recentPredictions as $prediction)
            <tr>
                <td>{{ $prediction->created_at->diffForHumans() }}</td>
                <td>{{ ucfirst($prediction->prediction_type) }}</td>
                <td>{{ $prediction->predicted_value }}</td>
                <td>{{ number_format($prediction->confidence * 100, 1) }}%</td>
                <td><span class="badge badge-{{ $prediction->accuracy > 0.7 ? 'success' : 'warning' }}">
                    {{ $prediction->accuracy > 0.7 ? 'High' : 'Medium' }}
                </span></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    new Chart(document.getElementById('threatTrendChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode($trendLabels) !!},
            datasets: [{
                label: 'Threats Detected',
                data: {!! json_encode($threatTrends) !!},
                borderColor: '#ef4444',
                backgroundColor: 'rgba(239,68,68,0.1)',
                fill: true
            }]
        }
    });
    
    new Chart(document.getElementById('anomalyChart'), {
        type: 'bar',
        data: {
            labels: ['Network', 'User Behavior', 'System', 'Application', 'Data'],
            datasets: [{
                label: 'Anomaly Score',
                data: {!! json_encode($anomalyScores) !!},
                backgroundColor: '#f59e0b'
            }]
        }
    });
</script>
@endpush
@endsection