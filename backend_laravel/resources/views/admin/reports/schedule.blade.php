@extends('admin.layouts.app')

@section('title', 'Schedule Reports')
@section('page-title', 'Scheduled Reports')

@section('content')
<div class="toolbar">
    <button id="addScheduleBtn" class="btn btn-primary">+ Add Schedule</button>
</div>

<div class="schedules-list">
    <table class="data-table">
        <thead>
            <tr><th>Report Name</th><th>Frequency</th><th>Next Run</th><th>Recipients</th><th>Status</th><th>Actions</th></tr>
        </thead>
        <tbody>
            @foreach($schedules as $schedule)
            <tr>
                <td>{{ $schedule->report_name }}</td>
                <td>{{ ucfirst($schedule->frequency) }}</td>
                <td>{{ $schedule->next_run_at ? $schedule->next_run_at->format('Y-m-d H:i') : '-' }}</td>
                <td>{{ implode(', ', array_slice($schedule->recipients, 0, 2)) }}{{ count($schedule->recipients) > 2 ? '...' : '' }}</td>
                <td><span class="badge badge-{{ $schedule->is_active ? 'success' : 'danger' }}">{{ $schedule->is_active ? 'Active' : 'Paused' }}</span></td>
                <td class="action-buttons">
                    <button class="btn btn-sm" onclick="editSchedule('{{ $schedule->id }}')">Edit</button>
                    <button class="btn btn-sm" onclick="toggleSchedule('{{ $schedule->id }}')">{{ $schedule->is_active ? 'Pause' : 'Resume' }}</button>
                    <form method="POST" action="{{ route('admin.reports.schedule.destroy', $schedule) }}" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this schedule?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Schedule Modal -->
<div id="scheduleModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalTitle">Add Schedule</h3>
            <button class="close" onclick="closeModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form id="scheduleForm">
                @csrf
                <input type="hidden" id="scheduleId" name="id">
                <div class="form-group">
                    <label>Report Name</label>
                    <input type="text" name="report_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Frequency</label>
                    <select name="frequency" class="form-control" required>
                        <option value="daily">Daily</option>
                        <option value="weekly">Weekly</option>
                        <option value="monthly">Monthly</option>
                        <option value="quarterly">Quarterly</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Time (UTC)</label>
                    <input type="time" name="time" class="form-control" value="08:00">
                </div>
                <div class="form-group">
                    <label>Recipients (comma separated)</label>
                    <input type="text" name="recipients" class="form-control" placeholder="email1@example.com, email2@example.com">
                </div>
                <div class="form-group">
                    <label>Report Format</label>
                    <select name="format" class="form-control">
                        <option value="pdf">PDF</option>
                        <option value="excel">Excel</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Save Schedule</button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('addScheduleBtn').addEventListener('click', () => {
        document.getElementById('modalTitle').textContent = 'Add Schedule';
        document.getElementById('scheduleForm').reset();
        document.getElementById('scheduleId').value = '';
        document.getElementById('scheduleModal').style.display = 'block';
    });
    
    async function editSchedule(id) {
        const response = await fetch(`/admin/reports/schedule/${id}`);
        const data = await response.json();
        
        document.getElementById('modalTitle').textContent = 'Edit Schedule';
        document.getElementById('scheduleId').value = data.id;
        document.querySelector('[name="report_name"]').value = data.report_name;
        document.querySelector('[name="frequency"]').value = data.frequency;
        document.querySelector('[name="time"]').value = data.time;
        document.querySelector('[name="recipients"]').value = data.recipients.join(', ');
        document.querySelector('[name="format"]').value = data.format;
        document.getElementById('scheduleModal').style.display = 'block';
    }
    
    document.getElementById('scheduleForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const id = document.getElementById('scheduleId').value;
        const url = id ? `/admin/reports/schedule/${id}` : '{{ route("admin.reports.schedule.store") }}';
        const method = id ? 'PUT' : 'POST';
        
        const response = await fetch(url, {
            method: method,
            body: formData,
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        });
        
        if (response.ok) {
            location.reload();
        } else {
            alert('Failed to save schedule');
        }
    });
    
    async function toggleSchedule(id) {
        const response = await fetch(`/admin/reports/schedule/${id}/toggle`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        });
        
        if (response.ok) {
            location.reload();
        }
    }
    
    function closeModal() {
        document.getElementById('scheduleModal').style.display = 'none';
    }
</script>
@endpush
@endsection