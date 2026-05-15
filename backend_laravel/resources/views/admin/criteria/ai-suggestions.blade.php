@extends('admin.layouts.app')

@section('title', 'AI Criteria Suggestions')
@section('page-title', 'AI-Generated Criteria Suggestions')

@section('content')
<div class="ai-generator">
    <div class="form-group">
        <label class="form-label">Domain</label>
        <select id="domainSelect" class="form-control">
            @foreach($domains as $domain)
            <option value="{{ $domain->id }}">{{ $domain->name }}</option>
            @endforeach
        </select>
    </div>
    
    <div class="form-group">
        <label class="form-label">Requirements Description</label>
        <textarea id="requirements" class="form-control" rows="5" 
            placeholder="Describe the security requirements for this domain..."></textarea>
    </div>
    
    <button id="generateBtn" class="btn btn-primary">🤖 Generate Suggestions</button>
    
    <div id="suggestions" class="suggestions-container" style="display: none;">
        <h3>AI Suggestions</h3>
        <div class="suggestions-list"></div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('generateBtn').addEventListener('click', async function() {
        const domainId = document.getElementById('domainSelect').value;
        const requirements = document.getElementById('requirements').value;
        
        if (!requirements) {
            alert('Please enter requirements description');
            return;
        }
        
        this.disabled = true;
        this.textContent = 'Generating...';
        
        try {
            const response = await fetch('{{ route("admin.criteria.ai-generate") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ domain_id: domainId, requirements })
            });
            
            const data = await response.json();
            displaySuggestions(data.suggestions);
        } catch (error) {
            alert('Failed to generate suggestions');
        } finally {
            this.disabled = false;
            this.textContent = 'Generate Suggestions';
        }
    });
    
    function displaySuggestions(suggestions) {
        const container = document.getElementById('suggestions');
        const list = container.querySelector('.suggestions-list');
        list.innerHTML = '';
        
        suggestions.forEach(suggestion => {
            const card = document.createElement('div');
            card.className = 'suggestion-card';
            card.innerHTML = `
                <div class="suggestion-name">${suggestion.name}</div>
                <div class="suggestion-description">${suggestion.description}</div>
                <div class="suggestion-actions">
                    <button class="btn btn-sm btn-primary" onclick="applySuggestion(${suggestion.id})">Apply</button>
                    <button class="btn btn-sm btn-secondary" onclick="dismissSuggestion(${suggestion.id})">Dismiss</button>
                </div>
            `;
            list.appendChild(card);
        });
        
        container.style.display = 'block';
    }
    
    async function applySuggestion(id) {
        const response = await fetch(`/admin/criteria/ai-suggestions/${id}/apply`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        });
        if (response.ok) {
            location.reload();
        }
    }
</script>
@endpush
@endsection