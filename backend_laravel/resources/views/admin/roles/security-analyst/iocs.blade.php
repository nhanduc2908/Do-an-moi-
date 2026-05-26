@extends('admin.layouts.app')

@section('title', 'IOC Management')
@section('page-title', 'Indicators of Compromise')

@section('content')
<div class="toolbar">
    <button class="btn btn-primary" onclick="showAddIoc()">+ Add IOC</button>
</div>
<table class="data-table">
    <thead><tr><th>Type</th><th>Value</th><th>Threat</th><th>Confidence</th><th>Added</th></tr></thead>
    <tbody>
        @foreach($iocs ?? [] as $ioc)
        <tr><td>{{ $ioc->type }}</td><td><code>{{ $ioc->value }}</code></td><td>{{ $ioc->threat }}</td><td>{{ $ioc->confidence }}%</td><td>{{ $ioc->created_at->diffForHumans() }}</td></tr>
        @endforeach
    </tbody>
</table>
@endsection