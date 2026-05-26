@extends('admin.layouts.app')

@section('title', 'Runbooks')
@section('page-title', 'Incident Runbooks')

@section('content')
<div class="runbook-list">
    @foreach($runbooks ?? [] as $runbook)
    <div class="runbook-card">
        <h3>{{ $runbook->name }}</h3>
        <p>{{ $runbook->description }}</p>
        <button class="btn btn-primary" onclick="executeRunbook('{{ $runbook->id }}')">Execute</button>
    </div>
    @endforeach
</div>

@push('scripts')
<script>
    function executeRunbook(id) { if(confirm('Execute this runbook?')) alert('Runbook executed'); }
</script>
@endpush
@endsection