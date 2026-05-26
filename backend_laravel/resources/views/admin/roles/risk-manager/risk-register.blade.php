@extends('admin.layouts.app')

@section('title', 'Risk Register')
@section('page-title', 'Risk Register')

@section('content')
<div class="toolbar"><button class="btn btn-primary" onclick="createRisk()">+ Add Risk</button></div>
<table class="data-table">
    <thead><tr><th>Risk</th><th>Category</th><th>Likelihood</th><th>Impact</th><th>Score</th><th>Status</th><th>Actions</th></tr></thead>
    <tbody>
        @foreach($risks ?? [] as $risk)
        <tr><td>{{ $risk->name }}</td><td>{{ $risk->category }}</td><td>{{ $risk->likelihood }}</td><td>{{ $risk->impact }}</td><td>{{ $risk->likelihood * $risk->impact }}</td><td>{{ $risk->status }}</td><td><button class="btn btn-sm" onclick="editRisk('{{ $risk->id }}')">Edit</button></td></tr>
        @endforeach
    </tbody>
</table>
@endsection