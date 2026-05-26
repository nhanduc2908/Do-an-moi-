@extends('admin.layouts.app')

@section('title', 'Team Management')
@section('page-title', 'Security Team')

@section('content')
<div class="team-grid">
    @foreach($teamMembers ?? [] as $member)
    <div class="team-card">
        <div class="avatar">{{ substr($member->name, 0, 1) }}</div>
        <h4>{{ $member->name }}</h4>
        <p>{{ $member->roles->first()->display_name ?? 'Member' }}</p>
        <button class="btn btn-sm" onclick="assignShift('{{ $member->id }}')">Assign Shift</button>
    </div>
    @endforeach
</div>
@endsection