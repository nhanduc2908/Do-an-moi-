@extends('admin.layouts.app')

@section('title', 'Forensics')
@section('page-title', 'Forensic Tools')

@section('content')
<div class="toolbar">
    <input type="text" id="targetSystem" class="form-control" placeholder="System ID or IP">
    <button id="collectBtn" class="btn btn-primary">Collect Forensic Data</button>
</div>
<div id="forensicResults"></div>
@endsection