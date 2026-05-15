<header class="top-bar">
    <div class="top-bar-title">
        @yield('page-title', 'Dashboard')
    </div>
    
    <div class="user-menu">
        <div class="notification-icon" id="notificationBtn">
            <span class="nav-icon">🔔</span>
            <span class="notification-badge" id="notificationCount">0</span>
        </div>
        
        <div class="user-dropdown">
            <div class="user-avatar">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
            <div class="user-info">
                <span class="user-name">{{ Auth::user()->name }}</span>
                <span class="user-role">{{ Auth::user()->roles->first()->display_name ?? 'User' }}</span>
            </div>
            <div class="dropdown-menu">
                <a href="{{ route('admin.profile') }}">Profile</a>
                <a href="{{ route('admin.settings.security') }}">Security Settings</a>
                <hr>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit">Logout</button>
                </form>
            </div>
        </div>
    </div>
</header>

<div id="notificationPanel" class="notification-panel" style="display: none;">
    <div class="notification-header">
        <h4>Notifications</h4>
        <button class="mark-all-read">Mark all as read</button>
    </div>
    <div class="notification-list">
        <div class="loading">Loading notifications...</div>
    </div>
</div>