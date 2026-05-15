@extends('admin.layouts.app')

@section('title', 'Reports')
@section('page-title', 'Security Reports')

@section('content')
<div class="toolbar">
    <a href="{{ route('admin.reports.generate') }}" class="btn btn-primary">+ Generate Report</a>
    <a href="{{ route('admin.reports.schedule') }}" class="btn btn-secondary">Schedule Reports</a>
</div>

<div class="reports-list">
    <table class="data-table">
        <thead>
            <tr><th>Report Name</th><th>Type</th><th>Format</th><th>Generated</th><th>Size</th><th>Downloads</th><th>Actions</th></tr>
        </thead>
        <tbody>
            @foreach($reports as $report)
            <tr>
                <td>{{ $report->report_name }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $report->report_type)) }}</td>
                <td>{{ strtoupper($report->format) }}</td>
                <td>{{ $report->generated_at->format('Y-m-d H:i') }}</td>
                <td>{{ number_format($report->file_size / 1024, 1) }} KB</td>
                <td>{{ $report->download_count }}</td>
                <td class="action-buttons">
                    <a href="{{ Storage::url($report->file_path) }}" class="btn btn-sm" download>Download</a>
                    <button class="btn btn-sm" onclick="shareReport('{{ $report->id }}')">Share</button>
                    <form method="POST" action="{{ route('admin.reports.destroy', $report) }}" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this report?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $reports->links() }}
</div>

<!-- Share Modal -->
<div id="shareModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Share Report</h3>
            <button class="close" onclick="closeShareModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form id="shareForm">
                @csrf
                <input type="hidden" id="shareReportId" name="report_id">
                <div class="form-group">
                    <label>Recipient Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Message (optional)</label>
                    <textarea name="message" class="form-control" rows="3"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Send</button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function shareReport(reportId) {
        document.getElementById('shareReportId').value = reportId;
        document.getElementById('shareModal').style.display = 'block';
    }
    
    document.getElementById('shareForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        const response = await fetch('{{ route("admin.reports.share") }}', {
            method: 'POST',
            body: formData,
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        });
        
        if (response.ok) {
            alert('Report shared successfully!');
            closeShareModal();
        } else {
            alert('Failed to share report');
        }
    });
    
    function closeShareModal() {
        document.getElementById('shareModal').style.display = 'none';
    }
</script>
@endpush
@endsection