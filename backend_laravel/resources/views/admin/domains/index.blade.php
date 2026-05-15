@extends('admin.layouts.app')

@section('title', 'Domains')
@section('page-title', 'Security Domains')

@section('content')
<div class="toolbar">
    <a href="{{ route('admin.domains.create') }}" class="btn btn-primary">+ Add Domain</a>
</div>

<div class="domains-grid">
    @foreach($domains as $domain)
    <div class="domain-card">
        <div class="domain-header">
            <span class="domain-code">{{ $domain->code }}</span>
            <div class="domain-actions">
                <a href="{{ route('admin.domains.edit', $domain) }}" class="btn-icon">✏️</a>
                <form method="POST" action="{{ route('admin.domains.destroy', $domain) }}" class="inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-icon" onclick="return confirm('Delete this domain?')">🗑️</button>
                </form>
            </div>
        </div>
        <div class="domain-name">{{ $domain->name }}</div>
        <div class="domain-weight">Weight: {{ $domain->weight }}%</div>
        <div class="domain-criteria">Criteria: {{ $domain->criteria_count ?? 0 }}</div>
        <div class="domain-description">{{ Str::limit($domain->description, 100) }}</div>
    </div>
    @endforeach
</div>
@endsection