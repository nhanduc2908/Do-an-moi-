@extends('admin.layouts.app')

@section('title', 'Risk Treatment')
@section('page-title', 'Risk Treatment Plan')

@section('content')
<table class="data-table">
    <thead><tr><th>Risk</th><th>Treatment Type</th><th>Action Plan</th><th>Owner</th><th>Due Date</th><th>Status</th></tr></thead>
    <tbody>
        @foreach($treatments ?? [] as $treatment)
        <tr><td>{{ $treatment->risk_name }}</td><td>{{ $treatment->type }}</td><td>{{ $treatment->plan }}</td><td>{{ $treatment->owner }}</td><td>{{ $treatment->due_date }}</td><td><span class="badge badge-{{ $treatment->status }}">{{ $treatment->status }}</span></td></tr>
        @endforeach
    </tbody>
</table>
@endsection