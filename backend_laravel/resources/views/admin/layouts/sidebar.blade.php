<aside class="admin-sidebar">
    <div class="sidebar-header">
        <h2>Security Platform</h2>
        <p class="version">v{{ config('version.app_version') }}</p>
    </div>
    
    <nav class="sidebar-nav">
        <ul>
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <span class="nav-icon">📊</span>
                    <span>Dashboard</span>
                </a>
            </li>
            
            <li class="nav-divider">Security Management</li>
            
            <li class="nav-item">
                <a href="{{ route('admin.security-score.index') }}" class="nav-link {{ request()->routeIs('admin.security-score.*') ? 'active' : '' }}">
                    <span class="nav-icon">🎯</span>
                    <span>Security Score</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('admin.assessments.index') }}" class="nav-link {{ request()->routeIs('admin.assessments.*') ? 'active' : '' }}">
                    <span class="nav-icon">📋</span>
                    <span>Assessments</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('admin.vulnerabilities.index') }}" class="nav-link {{ request()->routeIs('admin.vulnerabilities.*') ? 'active' : '' }}">
                    <span class="nav-icon">🔒</span>
                    <span>Vulnerabilities</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('admin.incidents.index') }}" class="nav-link {{ request()->routeIs('admin.incidents.*') ? 'active' : '' }}">
                    <span class="nav-icon">⚠️</span>
                    <span>Incidents</span>
                </a>
            </li>
            
            <li class="nav-divider">Configuration</li>
            
            <li class="nav-item">
                <a href="{{ route('admin.domains.index') }}" class="nav-link {{ request()->routeIs('admin.domains.*') ? 'active' : '' }}">
                    <span class="nav-icon">🏛️</span>
                    <span>Domains</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('admin.criteria.index') }}" class="nav-link {{ request()->routeIs('admin.criteria.*') ? 'active' : '' }}">
                    <span class="nav-icon">📝</span>
                    <span>Criteria</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('admin.keys.index') }}" class="nav-link {{ request()->routeIs('admin.keys.*') ? 'active' : '' }}">
                    <span class="nav-icon">🔑</span>
                    <span>Encryption Keys</span>
                </a>
            </li>
            
            <li class="nav-divider">Compliance</li>
            
            <li class="nav-item">
                <a href="{{ route('admin.compliance.index') }}" class="nav-link {{ request()->routeIs('admin.compliance.*') ? 'active' : '' }}">
                    <span class="nav-icon">📜</span>
                    <span>Compliance</span>
                </a>
            </li>
            
            <li class="nav-divider">Administration</li>
            
            <li class="nav-item">
                <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <span class="nav-icon">👥</span>
                    <span>Users</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('admin.roles.index') }}" class="nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                    <span class="nav-icon">🎭</span>
                    <span>Roles</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('admin.logs.security') }}" class="nav-link {{ request()->routeIs('admin.logs.*') ? 'active' : '' }}">
                    <span class="nav-icon">📄</span>
                    <span>Logs</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('admin.settings.general') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                    <span class="nav-icon">⚙️</span>
                    <span>Settings</span>
                </a>
            </li>
            
            <li class="nav-divider">AI & Sync</li>
            
            <li class="nav-item">
                <a href="{{ route('admin.ai.dashboard') }}" class="nav-link {{ request()->routeIs('admin.ai.*') ? 'active' : '' }}">
                    <span class="nav-icon">🤖</span>
                    <span>AI Engine</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('admin.sync.status') }}" class="nav-link {{ request()->routeIs('admin.sync.*') ? 'active' : '' }}">
                    <span class="nav-icon">🔄</span>
                    <span>Sync Status</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>