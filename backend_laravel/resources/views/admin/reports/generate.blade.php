@extends('admin.layouts.app')

@section('title', 'Generate Report')
@section('page-title', 'Generate Security Report')

@section('content')
<form method="POST" action="{{ route('admin.reports.store') }}" id="reportForm">
    @csrf
    
    <div class="form-group">
        <label class="form-label">Report Name</label>
        <input type="text" name="report_name" class="form-control" required placeholder="e.g., Q4 Security Summary 2024">
    </div>
    
    <div class="form-row">
        <div class="form-group">
            <label class="form-label">Report Type</label>
            <select name="report_type" class="form-control" required>
                <option value="security_summary">Security Summary</option>
                <option value="vulnerability_report">Vulnerability Report</option>
                <option value="compliance_report">Compliance Report</option>
                <option value="incident_report">Incident Report</option>
                <option value="executive_dashboard">Executive Dashboard</option>
            </select>
        </div>
        
        <div class="form-group">
            <label class="form-label">Format</label>
            <select name="format" class="form-control">
                <option value="pdf">PDF</option>
                <option value="excel">Excel</option>
                <option value="csv">CSV</option>
                <option value="json">JSON</option>
            </select>
        </div>
    </div>
    
    <div class="form-group">
        <label class="form-label">Date Range</label>
        <div class="date-range">
            <input type="date" name="date_from" class="form-control">
            <span>to</span>
            <input type="date" name="date_to" class="form-control">
        </div>
    </div>
    
    <div class="form-group">
        <label class="form-label">Sections to Include</label>
        <div class="checkbox-group">
            <label><input type="checkbox" name="sections[]" value="executive_summary" checked> Executive Summary</label>
            <label><input type="checkbox" name="sections[]" value="methodology" checked> Methodology</label>
            <label><input type="checkbox" name="sections[]" value="findings" checked> Findings</label>
            <label><input type="checkbox" name="sections[]" value="recommendations" checked> Recommendations</label>
            <label><input type="checkbox" name="sections[]" value="appendices"> Appendices</label>
        </div>
    </div>
    
    <div class="form-group">
        <label class="checkbox-label">
            <input type="checkbox" name="include_charts" value="1" checked>
            Include Charts and Graphs
        </label>
    </div>
    
    <div class="alert alert-info">
        <strong>Note:</strong> Large reports may take a few minutes to generate. You will receive an email when ready.
    </div>
    
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Generate Report</button>
        <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
</form>

<div id="progressModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Generating Report</h3>
        </div>
        <div class="modal-body">
            <div class="progress-container">
                <div class="progress-bar" id="generateProgress"></div>
            </div>
            <p id="progressMessage">Preparing report...</p>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('reportForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        document.getElementById('progressModal').style.display = 'block';
        const formData = new FormData(this);
        
        const response = await fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        });
        
        const data = await response.json();
        
        if (data.success) {
            document.getElementById('progressMessage').textContent = 'Report generated successfully! Redirecting...';
            setTimeout(() => {
                window.location.href = '{{ route("admin.reports.index") }}';
            }, 2000);
        } else {
            alert('Failed to generate report: ' + data.message);
            document.getElementById('progressModal').style.display = 'none';
        }
    });
</script>
@endpush
@endsection