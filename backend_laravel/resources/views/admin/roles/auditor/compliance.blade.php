@extends('admin.layouts.app')

@section('title', 'Compliance Checker')
@section('page-title', 'Compliance Verification')

@section('content')
<select id="standardSelect" class="form-control"><option value="iso27001">ISO 27001</option><option value="gdpr">GDPR</option><option value="pci_dss">PCI DSS</option></select>
<button id="runCheckBtn" class="btn btn-primary">Run Compliance Check</button>
<div id="complianceResult"></div>
@endsection