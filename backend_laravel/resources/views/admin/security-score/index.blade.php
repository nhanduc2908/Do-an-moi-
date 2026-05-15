@extends('admin.layouts.app')

@section('title', 'Security Score')
@section('page-title', 'Security Score Dashboard')

@section('content')
<div class="security-score-hero">
    <div class="score-circle">
        <div class="score-value">{{ $overallScore ?? 78 }}</div>
        <div class="score-label">Overall Security Score</div>
    </div>
    <div class="score-meta">
        <div class="meta-item">Last Updated: {{ $lastUpdated ?? now()->format('Y-m-d H:i') }}</div>
        <div class="meta-item">Trend: {{ $trend ?? 'Improving' }}</div>
    </div>
</div>

<div class="category-scores">
    <h3>Category Scores</h3>
    @foreach($categoryScores as $category => $score)
    <div class="category-item">
        <div class="category-name">{{ $category }}</div>
        <div class="progress-bar">
            <div class="progress-fill" style="width: {{ $score }}%"></div>
        </div>
        <div class="category-score">{{ $score }}%</div>
    </div>
    @endforeach
</div>

<div class="recommendations">
    <h3>Recommendations</h3>
    <ul>
        @foreach($recommendations as $rec)
        <li>{{ $rec }}</li>
        @endforeach
    </ul>
</div>
@endsection