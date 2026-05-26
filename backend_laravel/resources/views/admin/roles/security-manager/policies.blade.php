@extends('admin.layouts.app')

@section('title', 'Security Policies')
@section('page-title', 'Security Policies Management')

@section('content')
<div class="toolbar"><button class="btn btn-primary" onclick="createPolicy()">+ Create Policy</button></div>
<table class="data-table">
    <thead><tr><th>Name</th><th>Category</th><th>Status</th><th>Actions</th></tr></thead>
    <tbody>
        @foreach($policies ?? [] as $policy)
        <tr><td>{{ $policy->name }}</td><td>{{ $policy->category }}</td><td><span class="badge badge-{{ $policy->status }}">{{ $policy->status }}</span></td><td><button class="btn btn-sm" onclick="approvePolicy('{{ $policy->id }}')">Approve</button></td></tr>
        @endforeach
    </tbody>
</table>
@endsection