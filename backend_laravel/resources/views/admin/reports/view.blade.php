@extends('admin.layouts.app')

@section('title', 'Report Preview')
@section('page-title', 'Report: {{ $report->report_name }}')

@section('content')
<div class="report-viewer">
    <div class="report-actions">
        <a href="{{ Storage::url($report->file_path) }}" class="btn btn-primary" download>Download {{ strtoupper($report->format) }}</a>
        <button class="btn btn-secondary" onclick="window.print()">Print</button>
        <button class="btn btn-secondary" onclick="shareReport('{{ $report->id }}')">Share</button>
    </div>
    
    <div class="report-content">
        @if($report->format === 'pdf')
        <iframe src="{{ Storage::url($report->file_path) }}" width="100%" height="800px" style="border: none;"></iframe>
        @elseif($report->format === 'json')
        <pre class="json-viewer">{{ json_encode(json_decode(file_get_contents(Storage::path($report->file_path))), JSON_PRETTY_PRINT) }}</pre>
        @else
        <div class="alert alert-warning">Preview not available for this format. Please download to view.</div>
        @endif
    </div>
</div>
@endsection