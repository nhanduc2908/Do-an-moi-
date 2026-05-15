@extends('admin.layouts.app')

@section('title', 'PCI DSS Compliance')
@section('page-title', 'PCI DSS v4.0 Compliance')

@section('content')
<div class="pci-header">
    <div class="saq-info">
        <h3>SAQ Type: {{ $saqType }}</h3>
        <p>Last Assessment: {{ $lastAssessment ? $lastAssessment->format('Y-m-d') : 'Never' }}</p>
    </div>
    <div class="validation-level">
        <span class="level-badge">Level {{ $validationLevel }}</span>
    </div>
</div>

<div class="requirements-grid">
    @foreach($requirements as $req)
    <div class="requirement-card">
        <div class="req-header">
            <span class="req-number">Requirement {{ $req['number'] }}</span>
            <span class="req-status status-{{ $req['status'] }}">{{ $req['status'] }}</span>
        </div>
        <div class="req-name">{{ $req['name'] }}</div>
        <div class="req-progress">
            <div class="progress-bar">
                <div class="progress-fill" style="width: {{ $req['progress'] }}%"></div>
            </div>
            <div class="req-score">{{ $req['progress'] }}%</div>
        </div>
        @if($req['issues'])
        <div class="req-issues">
            <strong>Open Issues:</strong> {{ $req['issues'] }}
        </div>
        @endif
    </div>
    @endforeach
</div>

<div class="action-plan">
    <h3>Remediation Action Plan</h3>
    <table class="data-table">
        <thead>
            <tr><th>Requirement</th><th>Issue</th><th>Remediation</th><th>Priority</th><th>Target Date</th></tr>
        </thead>
        <tbody>
            @foreach($actionItems as $item)
            <tr>
                <td>{{ $item['requirement'] }}</td>
                <td>{{ $item['issue'] }}</td>
                <td>{{ $item['remediation'] }}</td>
                <td><span class="badge badge-{{ $item['priority'] }}">{{ $item['priority'] }}</span></td>
                <td>{{ $item['target_date'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection