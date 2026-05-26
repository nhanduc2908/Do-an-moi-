@extends('admin.layouts.app')

@section('title', 'System Settings')
@section('page-title', 'System Configuration')

@section('content')
<form method="POST" action="{{ route('admin.settings.update') }}">
    @csrf @method('PUT')
    <div class="form-group"><label>App Name</label><input type="text" name="app_name" class="form-control" value="{{ config('app.name') }}"></div>
    <div class="form-group"><label>App URL</label><input type="url" name="app_url" class="form-control" value="{{ config('app.url') }}"></div>
    <div class="form-group"><label>Time Zone</label><input type="text" name="timezone" class="form-control" value="{{ config('app.timezone') }}"></div>
    <button type="submit" class="btn btn-primary">Save Settings</button>
</form>
@endsection