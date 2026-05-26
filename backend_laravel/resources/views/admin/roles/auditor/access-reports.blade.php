@extends('admin.layouts.app')

@section('title', 'Access Reports')
@section('page-title', 'User Access Reports')

@section('content')
<input type="text" id="userId" class="form-control" placeholder="User ID or Email">
<button id="generateReportBtn" class="btn btn-primary">Generate Report</button>
<div id="accessReport"></div>
@endsection