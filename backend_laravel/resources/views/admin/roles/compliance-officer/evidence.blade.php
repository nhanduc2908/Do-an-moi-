@extends('admin.layouts.app')

@section('title', 'Evidence Collection')
@section('page-title', 'Compliance Evidence')

@section('content')
<form id="evidenceForm" enctype="multipart/form-data">
    @csrf
    <div class="form-group"><label>Control ID</label><input type="text" name="control_id" class="form-control" required></div>
    <div class="form-group"><label>Evidence File</label><input type="file" name="evidence" class="form-control" required></div>
    <button type="submit" class="btn btn-primary">Upload Evidence</button>
</form>

@push('scripts')
<script>
    document.getElementById('evidenceForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        await fetch('{{ route("compliance-officer.evidence.upload") }}', { method: 'POST', body: new FormData(e.target), headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });
        alert('Uploaded');
    });
</script>
@endpush
@endsection