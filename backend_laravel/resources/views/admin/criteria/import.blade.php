@extends('admin.layouts.app')

@section('title', 'Import Criteria')
@section('page-title', 'Import Criteria from JSON')

@section('content')
<form method="POST" action="{{ route('admin.criteria.import.process') }}" enctype="multipart/form-data">
    @csrf
    
    <div class="form-group">
        <label class="form-label">JSON File</label>
        <input type="file" name="file" class="form-control" accept=".json" required>
        <small>Upload a JSON file with criteria array</small>
    </div>
    
    <div class="form-group">
        <label class="checkbox-label">
            <input type="checkbox" name="overwrite" value="1">
            Overwrite existing criteria with same code
        </label>
    </div>
    
    <div class="alert alert-info">
        <strong>JSON Format Example:</strong>
        <pre>
[
    {
        "code": "AC-001",
        "name": "User Registration",
        "description": "...",
        "domain_code": "AC",
        "weight": 3,
        "max_score": 5,
        "passing_score": 3
    }
]
        </pre>
    </div>
    
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Import</button>
        <a href="{{ route('admin.criteria.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
</form>
@endsection