@extends('admin.layouts.app')

@section('title', 'Security Dashboard')
@section('page-title', 'Security Metrics Dashboard')

@section('content')
<canvas id="securityTrendChart" height="300"></canvas>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    new Chart(document.getElementById('securityTrendChart'), {
        type: 'line',
        data: { labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'], datasets: [{ label: 'Security Score', data: [65, 70, 68, 72, 75, 78], borderColor: '#1a56db' }] }
    });
</script>
@endpush
@endsection