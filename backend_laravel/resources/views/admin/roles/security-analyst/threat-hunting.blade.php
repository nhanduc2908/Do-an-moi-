@extends('admin.layouts.app')

@section('title', 'Threat Hunting')
@section('page-title', 'Threat Hunting Console')

@section('content')
<div class="filter-bar">
    <input type="text" id="huntQuery" class="form-control" placeholder="Enter hunting query (e.g., IP, hash, domain)">
    <button id="huntBtn" class="btn btn-primary">Start Hunt</button>
</div>
<div id="huntResults" class="hunt-results"></div>

@push('scripts')
<script>
    document.getElementById('huntBtn').addEventListener('click', async () => {
        let query = document.getElementById('huntQuery').value;
        let res = await fetch('/security-analyst/iocs/search?search=' + query);
        let data = await res.json();
        document.getElementById('huntResults').innerHTML = '<pre>' + JSON.stringify(data, null, 2) + '</pre>';
    });
</script>
@endpush
@endsection