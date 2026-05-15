
@extends('admin.layouts.app')

@section('title', 'Security Score Details')
@section('page-title', 'Security Score Details: {{ $domain ?? 'Overall' }}')

@section('content')
<div class="score-details">
    <div class="score-summary">
        <div class="current-score">
            <span class="label">Current Score</span>
            <span class="value">{{ $score }}%</span>
        </div>
        <div class="score-change">
            <span class="label">Change</span>
            <span class="value {{ $change >= 0 ? 'positive' : 'negative' }}">
                {{ $change >= 0 ? '+' : '' }}{{ $change }}%
            </span>
        </div>
    </div>
    
    <div class="score-breakdown">
        <h3>Score Breakdown</h3>
        <table class="data-table">
            <thead>
                <tr><th>Criteria</th><th>Weight</th><th>Score</th><th>Status</th></tr>
            </thead>
            <tbody>
                @foreach($criteriaScores as $criterion)
                <tr>
                    <td>{{ $criterion['name'] }}</td>
                    <td>{{ $criterion['weight'] }}</td>
                    <td>{{ $criterion['score'] }}</td>
                    <td><span class="badge badge-{{ $criterion['status'] }}">{{ $criterion['status'] }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="improvement-areas">
        <h3>Areas for Improvement</h3>
        <ul>
            @foreach($improvements as $improvement)
            <li>{{ $improvement }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endsection