@extends('admin.layouts.app')

@section('title', 'AI Predictions')
@section('page-title', 'Security Risk Predictions')

@section('content')
<div class="predictions-dashboard">
    <div class="prediction-header">
        <h3>Risk Forecast for Next 30 Days</h3>
        <div class="forecast-summary">
            <div class="forecast-item">
                <span class="forecast-label">Predicted Incidents</span>
                <span class="forecast-value">{{ $predictedIncidents }}</span>
            </div>
            <div class="forecast-item">
                <span class="forecast-label">Risk Trend</span>
                <span class="forecast-value {{ $riskTrend >= 0 ? 'up' : 'down' }}">
                    {{ $riskTrend >= 0 ? '↑' : '↓' }} {{ abs($riskTrend) }}%
                </span>
            </div>
            <div class="forecast-item">
                <span class="forecast-label">Confidence</span>
                <span class="forecast-value">{{ $confidence }}%</span>
            </div>
        </div>
    </div>
    
    <div class="widget">
        <div class="widget-title">Risk Score Prediction</div>
        <canvas id="riskPredictionChart" height="300"></canvas>
    </div>
    
    <div class="widget">
        <div class="widget-title">Vulnerability Forecast</div>
        <canvas id="vulnPredictionChart" height="300"></canvas>
    </div>
    
    <div class="widget">
        <div class="widget-title">AI Recommendations</div>
        <ul class="recommendations-list">
            @foreach($recommendations as $rec)
            <li>
                <span class="rec-priority priority-{{ $rec['priority'] }}">{{ ucfirst($rec['priority']) }}</span>
                <span class="rec-message">{{ $rec['message'] }}</span>
            </li>
            @endforeach
        </ul>
    </div>
    
    <div class="widget">
        <div class="widget-title">Historical vs Predicted</div>
        <table class="data-table">
            <thead>
                <tr><th>Month</th><th>Actual Incidents</th><th>Predicted</th><th>Variance</th></tr>
            </thead>
            <tbody>
                @foreach($historicalData as $data)
                <tr>
                    <td>{{ $data['month'] }}</td>
                    <td>{{ $data['actual'] }}</td>
                    <td>{{ $data['predicted'] }}</td>
                    <td>{{ $data['variance'] }}%</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    new Chart(document.getElementById('riskPredictionChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode($riskLabels) !!},
            datasets: [
                {
                    label: 'Historical Risk Score',
                    data: {!! json_encode($historicalRisk) !!},
                    borderColor: '#6b7280',
                    backgroundColor: 'transparent'
                },
                {
                    label: 'Predicted Risk Score',
                    data: {!! json_encode($predictedRisk) !!},
                    borderColor: '#ef4444',
                    backgroundColor: 'transparent',
                    borderDash: [5, 5]
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `${context.dataset.label}: ${context.raw}%`;
                        }
                    }
                }
            }
        }
    });
    
    new Chart(document.getElementById('vulnPredictionChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($vulnLabels) !!},
            datasets: [
                {
                    label: 'Critical',
                    data: {!! json_encode($criticalPred) !!},
                    backgroundColor: '#ef4444'
                },
                {
                    label: 'High',
                    data: {!! json_encode($highPred) !!},
                    backgroundColor: '#f59e0b'
                },
                {
                    label: 'Medium',
                    data: {!! json_encode($mediumPred) !!},
                    backgroundColor: '#3b82f6'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: { stacked: true },
                y: { stacked: true }
            }
        }
    });
</script>
@endpush
@endsection