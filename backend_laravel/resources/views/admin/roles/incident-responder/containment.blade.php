@extends('admin.layouts.app')

@section('title', 'Containment')
@section('page-title', 'Containment Actions')

@section('content')
<div class="containment-actions">
    <div class="action-card"><h4>Isolate System</h4><input type="text" id="isolateSystem" placeholder="System ID"><button onclick="isolate()">Isolate</button></div>
    <div class="action-card"><h4>Block IP</h4><input type="text" id="blockIp" placeholder="IP Address"><button onclick="blockIp()">Block</button></div>
</div>

@push('scripts')
<script>
    async function isolate() { await fetch('/incident-responder/systems/isolate', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: JSON.stringify({ system_id: document.getElementById('isolateSystem').value }) }); alert('System isolated'); }
    async function blockIp() { await fetch('/incident-responder/ip/block', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: JSON.stringify({ ip_address: document.getElementById('blockIp').value }) }); alert('IP blocked'); }
</script>
@endpush
@endsection