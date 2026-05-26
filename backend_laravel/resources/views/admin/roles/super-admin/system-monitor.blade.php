@extends('admin.layouts.app')

@section('title', 'System Monitor')
@section('page-title', 'System Health Monitor')

@section('content')
<div class="metrics-grid">
    <div class="metric-card">
        <div class="metric-title">CPU Usage</div>
        <div class="metric-value">{{ $cpu_usage ?? 0 }}%</div>
        <div class="progress-bar"><div class="progress-fill" style="width: {{ $cpu_usage ?? 0 }}%"></div></div>
    </div>
    <div class="metric-card">
        <div class="metric-title">Memory Usage</div>
        <div class="metric-value">{{ $memory_usage ?? 0 }}%</div>
        <div class="progress-bar"><div class="progress-fill" style="width: {{ $memory_usage ?? 0 }}%"></div></div>
    </div>
    <div class="metric-card">
        <div class="metric-title">Disk Usage</div>
        <div class="metric-value">{{ $disk_usage ?? 0 }}%</div>
        <div class="progress-bar"><div class="progress-fill" style="width: {{ $disk_usage ?? 0 }}%"></div></div>
    </div>
</div>

<div class="widget">
    <div class="widget-title">System Information</div>
    <table class="info-table">
        <tr><th>PHP Version:</th><td>{{ $php_version ?? 'Unknown' }}</tr>
        <tr><th>Laravel Version:</th><td>{{ $laravel_version ?? 'Unknown' }}</tr>
        <tr><th>MySQL Version:</th><td>{{ $mysql_version ?? 'Unknown' }}</tr>
        <tr><th>Queue Size:</th><td>{{ $queue_size ?? 0 }} jobs</tr>
    </table>
</div>

@push('scripts')
<script>setTimeout(() => location.reload(), 30000);</script>
@endpush
@endsection