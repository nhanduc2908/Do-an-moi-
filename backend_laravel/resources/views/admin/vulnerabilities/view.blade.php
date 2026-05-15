@extends('admin.layouts.app')

@section('title', 'Vulnerability Details')
@section('page-title', 'Vulnerability: {{ $vulnerability->cve_id }}')

@section('content')
<div class="vuln-header severity-{{ strtolower($vulnerability->severity) }}">
    <div class="header-main">
        <h2>{{ $vulnerability->title }}</h2>
        <div class="cvss-section">
            <span class="cvss-score">CVSS: {{ $vulnerability->cvss_score }}</span>
            <span class="cvss-vector">{{ $vulnerability->cvss_vector }}</span>
        </div>
    </div>
    <div class="header-badges">
        <span class="badge badge-{{ strtolower($vulnerability->severity) }}">{{ $vulnerability->severity }}</span>
        <span class="badge badge-{{ $vulnerability->status }}">{{ $vulnerability->status }}</span>
    </div>
</div>

<div class="vuln-details">
    <div class="detail-section">
        <h3>Description</h3>
        <p>{{ $vulnerability->description }}</p>
    </div>
    
    <div class="detail-section">
        <h3>Technical Details</h3>
        <table class="info-table">
            <tr><th>Attack Vector:</th><td>{{ $cveDetail->attack_vector ?? 'N/A' }}</td></tr>
            <tr><th>Attack Complexity:</th><td>{{ $cveDetail->attack_complexity ?? 'N/A' }}</td></tr>
            <tr><th>Privileges Required:</th><td>{{ $cveDetail->privileges_required ?? 'N/A' }}</td></tr>
            <tr><th>User Interaction:</th><td>{{ $cveDetail->user_interaction ?? 'N/A' }}</td></tr>
            <tr><th>Confidentiality Impact:</th><td>{{ $cveDetail->confidentiality_impact ?? 'N/A' }}</td></tr>
            <tr><th>Integrity Impact:</th><td>{{ $cveDetail->integrity_impact ?? 'N/A' }}</td></tr>
            <tr><th>Availability Impact:</th><td>{{ $cveDetail->availability_impact ?? 'N/A' }}</td></tr>
        </table>
    </div>
    
    <div class="detail-section">
        <h3>Affected Systems</h3>
        <ul>
            @foreach($affectedSystems as $system)
            <li>{{ $system->name }} - {{ $system->version }}</li>
            @endforeach
        </ul>
    </div>
    
    <div class="detail-section">
        <h3>Remediation</h3>
        <div class="remediation-box">
            @if($patch)
            <p><strong>Patch Available:</strong> {{ $patch->patch_name }} (v{{ $patch->patch_version }})</p>
            <p><strong>Release Date:</strong> {{ $patch->release_date->format('Y-m-d') }}</p>
            <a href="{{ $patch->download_url }}" class="btn btn-primary" target="_blank">Download Patch</a>
            @else
            <p>No patch available yet. Please follow vendor recommendations.</p>
            @endif
        </div>
    </div>
</div>

<div class="form-actions">
    @if($vulnerability->status === 'open')
    <a href="{{ route('admin.vulnerabilities.remediate', $vulnerability) }}" class="btn btn-primary">Start Remediation</a>
    @endif
    <a href="{{ route('admin.vulnerabilities.index') }}" class="btn btn-secondary">Back to List</a>
</div>
@endsection