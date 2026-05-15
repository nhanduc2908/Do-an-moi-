@extends('admin.layouts.app')

@section('title', 'Assessment Criteria')
@section('page-title', 'Assessment Criteria')

@section('content')
<div class="toolbar">
    <a href="{{ route('admin.criteria.create') }}" class="btn btn-primary">+ Add Criteria</a>
    <a href="{{ route('admin.criteria.import') }}" class="btn btn-secondary">Import</a>
    <a href="{{ route('admin.criteria.ai-suggestions') }}" class="btn btn-info">🤖 AI Suggestions</a>
</div>

<div class="filter-bar">
    <select id="domainFilter" class="form-control">
        <option value="">All Domains</option>
        @foreach($domains as $domain)
        <option value="{{ $domain->id }}">{{ $domain->name }}</option>
        @endforeach
    </select>
</div>

<div class="criteria-list">
    <table class="data-table">
        <thead>
            <tr><th>Code</th><th>Name</th><th>Domain</th><th>Weight</th><th>Passing Score</th><th>Actions</th></tr>
        </thead>
        <tbody>
            @foreach($criteria as $criterion)
            <tr data-domain="{{ $criterion->domain_id }}">
                <td>{{ $criterion->code }}</td>
                <td>{{ $criterion->name }}</td>
                <td>{{ $criterion->domain->name }}</td>
                <td>{{ $criterion->weight }}</td>
                <td>{{ $criterion->passing_score }}/{{ $criterion->max_score }}</td>
                <td class="action-buttons">
                    <a href="{{ route('admin.criteria.edit', $criterion) }}" class="btn btn-sm">Edit</a>
                    <form method="POST" action="{{ route('admin.criteria.destroy', $criterion) }}" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this criterion?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@push('scripts')
<script>
    document.getElementById('domainFilter').addEventListener('change', function() {
        const domainId = this.value;
        document.querySelectorAll('.criteria-list tbody tr').forEach(row => {
            if (!domainId || row.dataset.domain === domainId) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>
@endpush
@endsection