@extends('admin.layouts.app')

@section('title', 'ISO 27001 Compliance')
@section('page-title', 'ISO 27001:2022 Compliance Status')

@section('content')
<div class="standard-header">
    <h2>ISO/IEC 27001:2022</h2>
    <div class="overall-score">
        <div class="score-circle">{{ $overallCompliance }}%</div>
        <div class="score-label">Overall Compliance</div>
    </div>
</div>

<div class="annex-list">
    @foreach($annexes as $annex)
    <div class="annex-card">
        <div class="annex-header">
            <h4>{{ $annex['code'] }} - {{ $annex['name'] }}</h4>
            <span class="annex-score">{{ $annex['compliance'] }}%</span>
        </div>
        <div class="progress-bar">
            <div class="progress-fill" style="width: {{ $annex['compliance'] }}%"></div>
        </div>
        <div class="annex-controls">
            @foreach($annex['controls'] as $control)
            <div class="control-item">
                <div class="control-info">
                    <span class="control-id">{{ $control['id'] }}</span>
                    <span class="control-name">{{ $control['name'] }}</span>
                </div>
                <span class="control-status status-{{ $control['status'] }}">{{ $control['status'] }}</span>
            </div>
            @endforeach
        </div>
    </div>
    @endforeach
</div>

<div class="remediation-plan">
    <h3>Remediation Plan</h3>
    <table class="data-table">
        <thead>
            <tr><th>Control</th><th>Gap</th><th>Action Required</th><th>Responsible</th><th>Target Date</th></tr>
        </thead>
        <tbody>
            @foreach($gaps as $gap)
            <tr>
                <td>{{ $gap['control_id'] }}</td>
                <td>{{ $gap['description'] }}</td>
                <td>{{ $gap['remediation'] }}</td>
                <td>{{ $gap['owner'] }}</td>
                <td>{{ $gap['target_date'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="form-actions">
    <a href="{{ route('admin.compliance.export', 'iso27001') }}" class="btn btn-primary">Export SOA</a>
    <a href="{{ route('admin.compliance.audit', 'iso27001') }}" class="btn btn-secondary">Run Internal Audit</a>
</div>
@endsection