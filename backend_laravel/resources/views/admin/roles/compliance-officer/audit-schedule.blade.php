@extends('admin.layouts.app')

@section('title', 'Audit Schedule')
@section('page-title', 'Compliance Audit Schedule')

@section('content')
<table class="data-table">
    <thead><tr><th>Standard</th><th>Audit Date</th><th>Status</th><th>Actions</th></tr></thead>
    <tbody>
        <tr><td>ISO 27001</td><td>2025-06-15</td><td><span class="badge badge-warning">Pending</span></td><td><button class="btn btn-sm" onclick="runAudit('iso27001')">Run Audit</button></td></tr>
        <tr><td>GDPR</td><td>2025-07-01</td><td><span class="badge badge-warning">Pending</span></td><td><button class="btn btn-sm" onclick="runAudit('gdpr')">Run Audit</button></td></tr>
    </tbody>
</table>

@push('scripts')
<script>function runAudit(standard) { fetch('/compliance-officer/audit/run', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: JSON.stringify({ standard }) }).then(() => alert('Audit started')); }</script>
@endpush
@endsection