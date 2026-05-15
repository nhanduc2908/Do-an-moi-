<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Security Platform') - Admin Dashboard</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link href="{{ asset('assets/css/admin.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
    <div class="admin-wrapper">
        @include('admin.layouts.sidebar')
        
        <div class="admin-content">
            @include('admin.layouts.header')
            
            <main class="main-content">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade-in">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade-in">
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                @endif
                
                @yield('content')
            </main>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="{{ asset('assets/js/admin.js') }}"></script>
    @stack('scripts')
</body>
</html>