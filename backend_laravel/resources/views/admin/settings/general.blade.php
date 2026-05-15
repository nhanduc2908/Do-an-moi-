@extends('admin.layouts.app')

@section('title', 'General Settings')
@section('page-title', 'General System Settings')

@section('content')
<form method="POST" action="{{ route('admin.settings.update') }}">
    @csrf @method('PUT')
    
    <div class="settings-section">
        <h3>Application Settings</h3>
        
        <div class="form-group">
            <label class="form-label">Application Name</label>
            <input type="text" name="app_name" class="form-control" value="{{ setting('app_name', 'Security Platform') }}">
        </div>
        
        <div class="form-group">
            <label class="form-label">Application URL</label>
            <input type="url" name="app_url" class="form-control" value="{{ setting('app_url', config('app.url')) }}">
        </div>
        
        <div class="form-group">
            <label class="form-label">Time Zone</label>
            <select name="timezone" class="form-control">
                @foreach($timezones as $tz)
                <option value="{{ $tz }}" {{ setting('timezone') == $tz ? 'selected' : '' }}>{{ $tz }}</option>
                @endforeach
            </select>
        </div>
        
        <div class="form-group">
            <label class="form-label">Date Format</label>
            <select name="date_format" class="form-control">
                <option value="Y-m-d" {{ setting('date_format') == 'Y-m-d' ? 'selected' : '' }}>YYYY-MM-DD</option>
                <option value="m/d/Y" {{ setting('date_format') == 'm/d/Y' ? 'selected' : '' }}>MM/DD/YYYY</option>
                <option value="d/m/Y" {{ setting('date_format') == 'd/m/Y' ? 'selected' : '' }}>DD/MM/YYYY</option>
            </select>
        </div>
    </div>
    
    <div class="settings-section">
        <h3>Localization</h3>
        
        <div class="form-group">
            <label class="form-label">Default Language</label>
            <select name="locale" class="form-control">
                <option value="en" {{ setting('locale') == 'en' ? 'selected' : '' }}>English</option>
                <option value="vi" {{ setting('locale') == 'vi' ? 'selected' : '' }}>Tiếng Việt</option>
            </select>
        </div>
    </div>
    
    <div class="settings-section">
        <h3>Security Settings</h3>
        
        <div class="form-group">
            <label class="checkbox-label">
                <input type="checkbox" name="maintenance_mode" value="1" {{ setting('maintenance_mode') ? 'checked' : '' }}>
                Maintenance Mode
            </label>
        </div>
        
        <div class="form-group">
            <label class="checkbox-label">
                <input type="checkbox" name="debug_mode" value="1" {{ setting('debug_mode') ? 'checked' : '' }}>
                Debug Mode (Only for development)
            </label>
        </div>
    </div>
    
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Save Settings</button>
    </div>
</form>
@endsection