@extends('admin.layouts.app')

@section('title', 'AI Criteria Generator')
@section('page-title', 'AI-Powered Criteria Generation')

@section('content')
<div class="ai-generator">
    <div class="form-group">
        <label class="form-label">Select Domain</label>
        <select id="domainId" class="form-control">
            @foreach($domains as $domain)
            <option value="{{ $domain->id }}">{{ $domain->name }}</option>
            @endforeach
        </select>
    </div>
    
    <div class="form-group">
        <label class="form-label">Requirements Description</label>
        <textarea id="requirements" class="form-control" rows="8" 
            placeholder="Describe your security requirements in detail...
            
Example:
- We need to ensure that all user access is properly controlled and reviewed monthly
- Data encryption must be implemented for all sensitive data at rest and in transit
- Regular security awareness training for all employees"></textarea>
    </div>
    
    <div class="form-group">
        <label class="form-label">Complexity Level</label>
        <select id="complexity" class="form-control">
            <option value="basic">Basic - Simple criteria</option>
            <option value="standard" selected>Standard - Balanced detail</option>
            <option value="advanced">Advanced - Detailed technical criteria</option>
        </select>
    </div>
    
    <button id="generateBtn" class="btn btn-primary btn-lg">🤖 Generate Criteria</button>
    
    <div id="results" class="results-container" style="display: none;">
        <h3>AI-Generated Criteria</h3>
        <div id="criteriaList"></div>
        <div class="form-actions">
            <button id="applyAllBtn" class="btn btn-success">Apply All</button>
            <button id="regenerateBtn" class="btn btn-secondary">Regenerate</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('generateBtn').addEventListener('click', async function() {
        const domainId = document.getElementById('domainId').value;
        const requirements = document.getElementById('requirements').value;
        const complexity = document.getElementById('complexity').value;
        
        if (!requirements) {
            alert('Please enter requirements description');
            return;
        }
        
        this.disabled = true;
        this.textContent = 'Generating...';
        
        try {
            const response = await fetch('{{ route("admin.ai.criteria.generate") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    domain_id: domainId,
                    requirements: requirements,
                    complexity: complexity
                })
            });
            
            const data = await response.json();
            displayResults(data.criteria);
        } catch (error) {
            alert('Failed to generate criteria');
        } finally {
            this.disabled = false;
            this.textContent = 'Generate Criteria';
        }
    });
    
    function displayResults(criteria) {
        const container = document.getElementById('criteriaList');
        container.innerHTML = '';
        
        criteria.forEach((criterion, index) => {
            const card = document.createElement('div');
            card.className = 'criteria-card';
            card.innerHTML = `
                <div class="criteria-header">
                    <input type="checkbox" class="select-criterion" data-index="${index}">
                    <h4>${criterion.name}</h4>
                </div>
                <div class="criteria-body">
                    <p><strong>Code:</strong> ${criterion.code}</p>
                    <p><strong>Description:</strong> ${criterion.description}</p>
                    <p><strong>Weight:</strong> ${criterion.weight}</p>
                    <p><strong>Passing Score:</strong> ${criterion.passing_score}/${criterion.max_score}</p>
                </div>
            `;
            container.appendChild(card);
        });
        
        document.getElementById('results').style.display = 'block';
    }
    
    document.getElementById('applyAllBtn').addEventListener('click', async function() {
        const criteria = [];
        document.querySelectorAll('.select-criterion').forEach(checkbox => {
            if (checkbox.checked) {
                criteria.push(parseInt(checkbox.dataset.index));
            }
        });
        
        if (criteria.length === 0) {
            alert('Please select at least one criterion to apply');
            return;
        }
        
        const response = await fetch('{{ route("admin.ai.criteria.apply") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ criteria: criteria })
        });
        
        if (response.ok) {
            alert('Criteria added successfully!');
            window.location.href = '{{ route("admin.criteria.index") }}';
        } else {
            alert('Failed to add criteria');
        }
    });
    
    document.getElementById('regenerateBtn').addEventListener('click', function() {
        document.getElementById('generateBtn').click();
    });
</script>
@endpush
@endsection