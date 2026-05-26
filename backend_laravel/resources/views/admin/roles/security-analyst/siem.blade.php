@extends('admin.layouts.app')

@section('title', 'SIEM Dashboard')
@section('page-title', 'Security Information Event Management')

@section('content')
<div class="filter-bar">
    <textarea id="siemQuery" class="form-control" rows="3" placeholder="Enter SIEM query..."></textarea>
    <button id="runQueryBtn" class="btn btn-primary">Run Query</button>
</div>
<div id="siemResults"></div>

@push('scripts')
<script>
    document.getElementById('runQueryBtn').addEventListener('click', async () => {
        let query = document.getElementById('siemQuery').value;
        let res = await fetch('/security-analyst/siem/query', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: JSON.stringify({ query_string: query }) });
        let data = await res.json();
        document.getElementById('siemResults').innerHTML = '<pre>' + JSON.stringify(data, null, 2) + '</pre>';
    });
</script>
@endpush
@endsection