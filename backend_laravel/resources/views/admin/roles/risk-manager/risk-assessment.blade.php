@extends('admin.layouts.app')

@section('title', 'Risk Assessment')
@section('page-title', 'Risk Assessment Matrix')

@section('content')
<div id="riskMatrix" class="risk-matrix"></div>

@push('scripts')
<script>
    fetch('{{ route("risk-manager.matrix") }}').then(r=>r.json()).then(data=>{
        let html = '<table class="matrix-table">';
        for(let i=5;i>=1;i--){
            html += '<tr>';
            for(let j=1;j<=5;j++){
                let score = i*j;
                let level = score>=15?'critical':score>=8?'high':score>=4?'medium':'low';
                html += `<td class="risk-${level}">${score}</td>`;
            }
            html += '</tr>';
        }
        document.getElementById('riskMatrix').innerHTML = html;
    });
</script>
@endpush
@endsection