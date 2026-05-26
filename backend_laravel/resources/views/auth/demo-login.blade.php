<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Demo Login - Security Assessment Platform</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }
        .container {
            max-width: 1400px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 50px;
        }
        .header h1 {
            color: white;
            font-size: 2.5rem;
            margin-bottom: 10px;
        }
        .header p {
            color: #94a3b8;
            font-size: 1rem;
        }
        .demo-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 24px;
        }
        .demo-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s;
            cursor: pointer;
        }
        .demo-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 30px -12px rgba(0,0,0,0.3);
        }
        .card-header {
            padding: 24px;
            text-align: center;
            color: white;
        }
        .card-icon {
            font-size: 48px;
            margin-bottom: 12px;
        }
        .card-role {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 5px;
        }
        .card-level {
            font-size: 0.75rem;
            opacity: 0.8;
        }
        .card-body {
            padding: 20px;
            background: #f8fafc;
        }
        .demo-email {
            font-family: 'Courier New', monospace;
            font-size: 0.8rem;
            background: #e2e8f0;
            padding: 10px 12px;
            border-radius: 10px;
            margin-bottom: 10px;
            word-break: break-all;
            text-align: center;
        }
        .demo-password {
            font-family: 'Courier New', monospace;
            font-size: 0.75rem;
            color: #64748b;
            text-align: center;
            margin-bottom: 15px;
        }
        .login-btn {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            background: #1a56db;
            color: white;
        }
        .login-btn:hover {
            background: #1e429f;
        }
        .custom-login {
            text-align: center;
            margin-top: 40px;
        }
        .custom-login a {
            color: #94a3b8;
            text-decoration: none;
        }
        .custom-login a:hover {
            color: white;
        }
        .alert {
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 30px;
        }
        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border-left: 4px solid #ef4444;
        }
        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border-left: 4px solid #10b981;
        }
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 500;
        }
        @media (max-width: 768px) {
            .demo-grid {
                grid-template-columns: 1fr;
            }
            .header h1 {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔐 Security Assessment Platform</h1>
            <p>Select a demo account to explore the system features</p>
        </div>

        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="demo-grid">
            @foreach($demoAccounts as $account)
            <div class="demo-card" onclick="location.href='{{ route('demo.quick-login', str_replace(' ', '_', strtolower($account['role']))) }}'">
                <div class="card-header" style="background: {{ $account['color'] }}">
                    <div class="card-icon">{{ $account['icon'] }}</div>
                    <div class="card-role">{{ $account['role'] }}</div>
                    <div class="card-level">Level {{ $account['level'] }}</div>
                </div>
                <div class="card-body">
                    <div class="demo-email">{{ $account['email'] }}</div>
                    <div class="demo-password">🔑 {{ $account['password'] }}</div>
                    <button class="login-btn" onclick="event.stopPropagation(); login('{{ $account['email'] }}', '{{ $account['password'] }}')">
                        Login as {{ $account['role'] }}
                    </button>
                </div>
            </div>
            @endforeach
        </div>

        <div class="custom-login">
            <a href="{{ route('login') }}">← Back to Custom Login</a>
        </div>
    </div>

    <form id="demoLoginForm" method="POST" action="{{ route('demo.login') }}" style="display: none;">
        @csrf
        <input type="email" name="email" id="demoEmail">
        <input type="password" name="password" id="demoPassword">
        <input type="checkbox" name="remember" checked>
    </form>

    <script>
        function login(email, password) {
            document.getElementById('demoEmail').value = email;
            document.getElementById('demoPassword').value = password;
            document.getElementById('demoLoginForm').submit();
        }
    </script>
</body>
</html>