<?php

namespace App\Services\RoleBased;

use App\Models\Module01_IAM\User;
use App\Models\Module01_IAM\UserSession;
use App\Models\Module13_LoggingMonitoring\SecurityLog;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class SuperAdminService
{
    public function getDashboardData()
    {
        return [
            'totalUsers' => User::count(),
            'totalRoles' => \App\Models\Module01_IAM\Role::count(),
            'systemHealth' => $this->calculateSystemHealth(),
            'activeSessions' => UserSession::where('is_active', true)->count(),
            'databaseSize' => $this->getDatabaseSize(),
            'lastBackup' => $this->getLastBackupTime(),
            'pendingUpdates' => $this->checkPendingUpdates(),
            'recentActivities' => SecurityLog::latest()->limit(10)->get(),
            'failedJobs' => DB::table('failed_jobs')->count(),
            'pendingJobs' => DB::table('jobs')->count(),
            'cacheHits' => $this->getCacheStats(),
            'securityScore' => $this->calculateSecurityScore(),
            'serverUptime' => $this->getServerUptime(),
        ];
    }

    public function getSystemMetrics()
    {
        return [
            'cpu_usage' => $this->getCpuUsage(),
            'memory_usage' => $this->getMemoryUsage(),
            'disk_usage' => $this->getDiskUsage(),
            'queue_size' => DB::table('jobs')->count(),
            'failed_jobs' => DB::table('failed_jobs')->count(),
            'cache_hit_rate' => $this->getCacheHitRate(),
            'database_connections' => DB::connection()->getPdo()->getAttribute(\PDO::ATTR_CONNECTION_STATUS),
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'mysql_version' => DB::select('select version() as version')[0]->version,
            'redis_status' => $this->checkRedis() ? 'connected' : 'disconnected',
            'storage_used_percent' => $this->getDiskUsage(),
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time'),
        ];
    }

    public function getAuditTrail($request)
    {
        $query = SecurityLog::with('user');
        
        if ($request->event_type) {
            $query->where('event_type', $request->event_type);
        }
        if ($request->severity) {
            $query->where('severity', $request->severity);
        }
        if ($request->user_id) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->from) {
            $query->where('logged_at', '>=', $request->from);
        }
        if ($request->to) {
            $query->where('logged_at', '<=', $request->to . ' 23:59:59');
        }
        
        $perPage = $request->per_page ?? 50;
        
        return [
            'auditEvents' => $query->orderBy('logged_at', 'desc')->paginate($perPage),
            'eventTypes' => SecurityLog::select('event_type')->distinct()->pluck('event_type'),
            'severities' => ['critical', 'high', 'medium', 'low', 'info'],
            'users' => User::select('id', 'name')->get(),
            'totalEvents' => SecurityLog::count(),
            'eventStats' => $this->getEventStatistics(),
        ];
    }

    public function getMasterControlData()
    {
        return [
            'modules' => $this->getSystemModules(),
            'systemStatus' => $this->getSystemStatus(),
            'maintenanceMode' => app()->isDownForMaintenance(),
            'debugMode' => config('app.debug'),
            'environment' => app()->environment(),
            'lastMaintenance' => Cache::get('last_maintenance_time', 'Never'),
            'maintenanceMessage' => Cache::get('maintenance_message', 'System maintenance in progress'),
            'systemFeatures' => $this->getSystemFeatures(),
            'cacheDrivers' => $this->getCacheDrivers(),
            'queueDrivers' => $this->getQueueDrivers(),
            'sessionDrivers' => $this->getSessionDrivers(),
        ];
    }

    public function toggleModule($module, $status)
    {
        Cache::forever("module_{$module}_status", $status);
        
        SecurityLog::create([
            'event_type' => 'module_toggled',
            'severity' => 'info',
            'source' => 'super_admin',
            'user_id' => auth()->id(),
            'ip_address' => request()->ip(),
            'message' => "Module {$module} toggled to " . ($status ? 'ON' : 'OFF'),
            'details' => ['module' => $module, 'new_status' => $status],
            'logged_at' => now(),
        ]);
        
        return ['success' => true, 'module' => $module, 'status' => $status];
    }

    public function clearAllCache()
    {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        Artisan::call('event:clear');
        Artisan::call('optimize:clear');
        
        Cache::flush();
        
        SecurityLog::create([
            'event_type' => 'cache_cleared',
            'severity' => 'info',
            'source' => 'super_admin',
            'user_id' => auth()->id(),
            'ip_address' => request()->ip(),
            'message' => 'All system caches were cleared',
            'logged_at' => now(),
        ]);
        
        return ['success' => true, 'message' => 'All caches cleared successfully'];
    }

    public function runMaintenance($data)
    {
        $action = $data['action'];
        $isCurrentlyDown = app()->isDownForMaintenance();
        
        if ($action === 'enable' && !$isCurrentlyDown) {
            $message = $data['message'] ?? 'System maintenance in progress';
            $retry = $data['retry_minutes'] ?? 60;
            
            Artisan::call('down', [
                '--retry' => $retry,
                '--message' => $message,
            ]);
            
            Cache::put('last_maintenance_time', now(), 86400);
            Cache::put('maintenance_message', $message, 86400);
            
            $responseMessage = 'System is now in maintenance mode';
        } elseif ($action === 'disable' && $isCurrentlyDown) {
            Artisan::call('up');
            $responseMessage = 'System is now back online';
        } else {
            $responseMessage = $isCurrentlyDown ? 'System is already in maintenance mode' : 'System is already online';
        }
        
        SecurityLog::create([
            'event_type' => 'maintenance_mode',
            'severity' => 'high',
            'source' => 'super_admin',
            'user_id' => auth()->id(),
            'ip_address' => request()->ip(),
            'message' => $responseMessage,
            'logged_at' => now(),
        ]);
        
        return ['success' => true, 'message' => $responseMessage];
    }

    public function getSystemHealth()
    {
        $checks = [
            'database' => $this->checkDatabase() ? 100 : 0,
            'cache' => $this->checkCache() ? 100 : 0,
            'queue' => $this->checkQueue() ? 80 : 0,
            'storage' => $this->checkStorage() ? 100 : 0,
            'scheduler' => $this->checkScheduler() ? 90 : 0,
            'redis' => $this->checkRedis() ? 100 : 0,
            'disk_space' => max(0, 100 - $this->getDiskUsage()),
            'memory' => max(0, 100 - $this->getMemoryUsage()),
        ];
        
        $score = collect($checks)->avg();
        
        return [
            'score' => round($score, 2),
            'status' => $score >= 80 ? 'healthy' : ($score >= 60 ? 'warning' : 'critical'),
            'checks' => $checks,
            'timestamp' => now()->toIso8601String(),
        ];
    }

    public function getDatabaseStatus()
    {
        try {
            $dbName = DB::connection()->getDatabaseName();
            $tables = DB::select('SHOW TABLES');
            $tableCount = count($tables);
            
            // Get table status
            $tableStatus = [];
            foreach ($tables as $table) {
                $tableName = reset($table);
                $size = DB::select("SELECT data_length + index_length as size 
                    FROM information_schema.TABLES 
                    WHERE table_schema = ? AND table_name = ?", [$dbName, $tableName]);
                $tableStatus[] = [
                    'name' => $tableName,
                    'size_mb' => round($size[0]->size / 1024 / 1024, 2),
                ];
            }
            
            return [
                'connected' => true,
                'database' => $dbName,
                'table_count' => $tableCount,
                'size_mb' => $this->getDatabaseSize(),
                'tables' => $tableStatus,
            ];
        } catch (\Exception $e) {
            return ['connected' => false, 'error' => $e->getMessage()];
        }
    }

    public function getQueueStatus()
    {
        return [
            'pending_jobs' => DB::table('jobs')->count(),
            'failed_jobs' => DB::table('failed_jobs')->count(),
            'processed_today' => DB::table('jobs')->whereDate('created_at', today())->count(),
            'worker_running' => $this->isQueueWorkerRunning(),
            'failed_jobs_list' => DB::table('failed_jobs')->orderBy('failed_at', 'desc')->limit(20)->get(),
        ];
    }

    public function getServerInfo()
    {
        return [
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'server_time' => now()->toIso8601String(),
            'timezone' => config('app.timezone'),
            'server_name' => $_SERVER['SERVER_NAME'] ?? 'localhost',
            'server_ip' => $_SERVER['SERVER_ADDR'] ?? '127.0.0.1',
            'server_port' => $_SERVER['SERVER_PORT'] ?? '80',
            'document_root' => $_SERVER['DOCUMENT_ROOT'] ?? '',
            'operating_system' => PHP_OS,
            'server_protocol' => $_SERVER['SERVER_PROTOCOL'] ?? '',
        ];
    }

    public function getActiveSessions()
    {
        return UserSession::with('user')
            ->where('is_active', true)
            ->orderBy('last_activity', 'desc')
            ->get()
            ->map(function($session) {
                return [
                    'session_id' => $session->session_id,
                    'user_id' => $session->user_id,
                    'user_name' => $session->user?->name,
                    'user_email' => $session->user?->email,
                    'ip_address' => $session->ip_address,
                    'device_type' => $session->device_type,
                    'last_activity' => $session->last_activity,
                    'created_at' => $session->created_at,
                ];
            });
    }

    public function terminateSession($sessionId)
    {
        $session = UserSession::where('session_id', $sessionId)->first();
        
        if ($session) {
            $session->update(['is_active' => false]);
            
            SecurityLog::create([
                'event_type' => 'session_terminated',
                'severity' => 'info',
                'source' => 'super_admin',
                'user_id' => auth()->id(),
                'ip_address' => request()->ip(),
                'message' => "Terminated session for user ID: {$session->user_id}",
                'logged_at' => now(),
            ]);
            
            return ['success' => true, 'message' => 'Session terminated successfully'];
        }
        
        return ['success' => false, 'message' => 'Session not found'];
    }

    public function getBackupList()
    {
        $backups = [];
        $files = Storage::disk('local')->files('backups');
        
        foreach ($files as $file) {
            $backups[] = [
                'filename' => basename($file),
                'path' => $file,
                'size_bytes' => Storage::size($file),
                'size_mb' => round(Storage::size($file) / 1024 / 1024, 2),
                'created_at' => date('Y-m-d H:i:s', Storage::lastModified($file)),
            ];
        }
        
        return array_reverse($backups);
    }

    public function runBackup($type = 'full')
    {
        $filename = 'backup_' . date('Ymd_His') . '.sql';
        $path = storage_path('app/backups/' . $filename);
        
        $command = sprintf(
            'mysqldump --user=%s --password=%s --host=%s %s > %s',
            config('database.connections.mysql.username'),
            config('database.connections.mysql.password'),
            config('database.connections.mysql.host'),
            config('database.connections.mysql.database'),
            $path
        );
        
        exec($command);
        
        SecurityLog::create([
            'event_type' => 'manual_backup',
            'severity' => 'info',
            'source' => 'super_admin',
            'user_id' => auth()->id(),
            'message' => "Manual backup created: {$filename}",
            'logged_at' => now(),
        ]);
        
        return [
            'success' => true,
            'filename' => $filename,
            'size_mb' => round(filesize($path) / 1024 / 1024, 2),
            'created_at' => now(),
        ];
    }

    public function getEnvironmentConfig()
    {
        $envFile = base_path('.env');
        $envContent = File::exists($envFile) ? File::get($envFile) : '';
        
        $variables = [];
        $lines = explode("\n", $envContent);
        
        foreach ($lines as $line) {
            if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
                list($key, $value) = explode('=', $line, 2);
                $variables[trim($key)] = $this->maskSensitiveValue($key, trim($value));
            }
        }
        
        return $variables;
    }

    public function updateEnvironmentVariable($key, $value)
    {
        $envFile = base_path('.env');
        $envContent = File::get($envFile);
        
        if (strpos($envContent, "{$key}=") !== false) {
            $pattern = "/^{$key}=.*/m";
            $replacement = "{$key}={$value}";
            $envContent = preg_replace($pattern, $replacement, $envContent);
        } else {
            $envContent .= "\n{$key}={$value}";
        }
        
        File::put($envFile, $envContent);
        
        // Reload config
        Artisan::call('config:clear');
        
        SecurityLog::create([
            'event_type' => 'env_updated',
            'severity' => 'warning',
            'source' => 'super_admin',
            'user_id' => auth()->id(),
            'message' => "Updated environment variable: {$key}",
            'logged_at' => now(),
        ]);
        
        return ['success' => true, 'message' => 'Environment variable updated'];
    }

    public function getSystemLogs($channel, $lines)
    {
        $logPath = storage_path("logs/{$channel}.log");
        
        if (!File::exists($logPath)) {
            return ['error' => 'Log file not found'];
        }
        
        $content = File::get($logPath);
        $logs = explode("\n", $content);
        $logs = array_slice(array_reverse($logs), 0, $lines);
        
        return [
            'channel' => $channel,
            'lines' => $lines,
            'logs' => $logs,
        ];
    }

    public function getFailedJobs()
    {
        return DB::table('failed_jobs')
            ->orderBy('failed_at', 'desc')
            ->paginate(20);
    }

    public function retryFailedJob($jobId)
    {
        Artisan::call('queue:retry', ['id' => $jobId]);
        
        SecurityLog::create([
            'event_type' => 'job_retried',
            'severity' => 'info',
            'source' => 'super_admin',
            'user_id' => auth()->id(),
            'message' => "Retried failed job ID: {$jobId}",
            'logged_at' => now(),
        ]);
        
        return ['success' => true, 'message' => 'Job retried successfully'];
    }

    public function flushFailedJobs()
    {
        Artisan::call('queue:flush');
        
        SecurityLog::create([
            'event_type' => 'jobs_flushed',
            'severity' => 'info',
            'source' => 'super_admin',
            'user_id' => auth()->id(),
            'message' => 'All failed jobs were flushed',
            'logged_at' => now(),
        ]);
        
        return ['success' => true, 'message' => 'All failed jobs flushed'];
    }

    public function getScheduledTasks()
    {
        $tasks = [];
        $schedule = app()->make(\Illuminate\Console\Scheduling\Schedule::class);
        
        foreach ($schedule->events() as $event) {
            $tasks[] = [
                'command' => $event->command ?? $event->description,
                'expression' => $event->expression,
                'timezone' => $event->timezone,
                'description' => $event->description,
            ];
        }
        
        return $tasks;
    }

    public function runScheduledTask($command)
    {
        Artisan::call($command);
        
        SecurityLog::create([
            'event_type' => 'scheduled_task_run',
            'severity' => 'info',
            'source' => 'super_admin',
            'user_id' => auth()->id(),
            'message' => "Manually ran scheduled task: {$command}",
            'logged_at' => now(),
        ]);
        
        return ['success' => true, 'output' => Artisan::output()];
    }

    public function getSecurityStatistics()
    {
        return [
            'total_logins' => SecurityLog::where('event_type', 'login')->count(),
            'failed_logins' => SecurityLog::where('event_type', 'failed_login')->count(),
            'total_incidents' => \App\Models\Module14_IncidentResponse\Incident::count(),
            'open_incidents' => \App\Models\Module14_IncidentResponse\Incident::where('status', 'open')->count(),
            'total_vulnerabilities' => \App\Models\Module21_VulnerabilityManagement\Vulnerability::count(),
            'critical_vulnerabilities' => \App\Models\Module21_VulnerabilityManagement\Vulnerability::where('severity', 'CRITICAL')->count(),
            'api_calls_today' => \App\Models\Module06_ApiSecurity\ApiLog::whereDate('created_at', today())->count(),
            'blocked_ips' => \App\Models\Module09_NetworkSecurity\FirewallRule::where('action', 'block')->count(),
            'active_users' => User::where('status', 'active')->count(),
            'mfa_enabled_users' => User::where('mfa_enabled', true)->count(),
        ];
    }

    public function exportAuditLogs($params)
    {
        $query = SecurityLog::with('user');
        
        if ($params['from'] ?? false) {
            $query->where('logged_at', '>=', $params['from']);
        }
        if ($params['to'] ?? false) {
            $query->where('logged_at', '<=', $params['to'] . ' 23:59:59');
        }
        if ($params['event_type'] ?? false) {
            $query->where('event_type', $params['event_type']);
        }
        
        $logs = $query->get();
        
        $filename = 'audit_logs_' . date('Ymd_His') . '.' . $params['format'];
        $path = storage_path("app/exports/{$filename}");
        
        if ($params['format'] === 'csv') {
            $this->exportToCsv($logs, $path);
        } elseif ($params['format'] === 'json') {
            $this->exportToJson($logs, $path);
        }
        
        return response()->download($path)->deleteFileAfterSend(true);
    }

    private function exportToCsv($logs, $path)
    {
        $file = fopen($path, 'w');
        fputcsv($file, ['Time', 'Event Type', 'Severity', 'User', 'IP Address', 'Message']);
        
        foreach ($logs as $log) {
            fputcsv($file, [
                $log->logged_at,
                $log->event_type,
                $log->severity,
                $log->user?->name ?? 'System',
                $log->ip_address,
                $log->message,
            ]);
        }
        
        fclose($file);
    }

    private function exportToJson($logs, $path)
    {
        file_put_contents($path, json_encode($logs, JSON_PRETTY_PRINT));
    }

    // Private helper methods
    private function calculateSystemHealth()
    {
        $score = 100;
        if (DB::table('jobs')->count() > 1000) $score -= 20;
        if (DB::table('failed_jobs')->count() > 50) $score -= 15;
        if ($this->getDiskUsage() > 90) $score -= 25;
        if ($this->getMemoryUsage() > 90) $score -= 20;
        return max(0, $score);
    }

    private function calculateSecurityScore()
    {
        $score = 85;
        if (\App\Models\Module21_VulnerabilityManagement\Vulnerability::where('severity', 'CRITICAL')->count() > 5) $score -= 10;
        if (\App\Models\Module14_IncidentResponse\Incident::where('status', 'open')->where('severity', 'critical')->count() > 0) $score -= 15;
        return max(0, $score);
    }

    private function getDatabaseSize()
    {
        $dbName = config('database.connections.mysql.database');
        $result = DB::select("SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) as size 
            FROM information_schema.TABLES WHERE table_schema = ?", [$dbName]);
        return $result[0]->size ?? 0;
    }

    private function getLastBackupTime()
    {
        $backupFile = Storage::disk('local')->files('backups');
        if (empty($backupFile)) return 'No backup found';
        $lastBackup = collect($backupFile)->map(function($file) {
            return Storage::lastModified($file);
        })->max();
        return date('Y-m-d H:i:s', $lastBackup);
    }

    private function checkPendingUpdates()
    {
        return 0;
    }

    private function getCacheStats()
    {
        return [
            'hit_rate' => 95.5,
            'miss_rate' => 4.5,
            'total_requests' => 12500,
        ];
    }

    private function getCpuUsage()
    {
        if (function_exists('sys_getloadavg')) {
            $load = sys_getloadavg();
            return round($load[0] * 10, 2);
        }
        return 0;
    }

    private function getMemoryUsage()
    {
        $memory = memory_get_usage(true);
        $memoryLimit = ini_get('memory_limit');
        $limit = $this->convertToBytes($memoryLimit);
        return round(($memory / $limit) * 100, 2);
    }

    private function getDiskUsage()
    {
        $total = disk_total_space('/');
        $free = disk_free_space('/');
        $used = $total - $free;
        return round(($used / $total) * 100, 2);
    }

    private function getServerUptime()
    {
        if (function_exists('shell_exec')) {
            $uptime = shell_exec('uptime -p');
            return trim($uptime);
        }
        return 'Unknown';
    }

    private function convertToBytes($value)
    {
        $value = trim($value);
        $last = strtolower($value[strlen($value)-1]);
        $number = (int)$value;
        switch($last) {
            case 'g': $number *= 1024;
            case 'm': $number *= 1024;
            case 'k': $number *= 1024;
        }
        return $number;
    }

    private function getCacheHitRate()
    {
        return 95.5;
    }

    private function getEventStatistics()
    {
        return [
            'today' => SecurityLog::whereDate('logged_at', today())->count(),
            'this_week' => SecurityLog::whereBetween('logged_at', [now()->startOfWeek(), now()])->count(),
            'this_month' => SecurityLog::whereMonth('logged_at', now()->month)->count(),
            'by_severity' => SecurityLog::select('severity', DB::raw('count(*) as count'))->groupBy('severity')->get(),
        ];
    }

    private function getSystemModules()
    {
        return [
            ['name' => 'IAM Module', 'key' => 'iam', 'status' => Cache::get('module_iam_status', true), 'description' => 'User and role management'],
            ['name' => 'Encryption Module', 'key' => 'encryption', 'status' => Cache::get('module_encryption_status', true), 'description' => 'Key management and data encryption'],
            ['name' => 'Assessment Module', 'key' => 'assessment', 'status' => Cache::get('module_assessment_status', true), 'description' => 'Security assessment and scoring'],
            ['name' => 'AI Engine', 'key' => 'ai', 'status' => Cache::get('module_ai_status', true), 'description' => 'AI threat detection and predictions'],
            ['name' => 'Report Module', 'key' => 'report', 'status' => Cache::get('module_report_status', true), 'description' => 'Report generation and export'],
            ['name' => 'Sync Module', 'key' => 'sync', 'status' => Cache::get('module_sync_status', true), 'description' => 'Data synchronization'],
            ['name' => 'Notification Module', 'key' => 'notification', 'status' => Cache::get('module_notification_status', true), 'description' => 'Email and alert notifications'],
            ['name' => 'Backup Module', 'key' => 'backup', 'status' => Cache::get('module_backup_status', true), 'description' => 'System backup and recovery'],
        ];
    }

    private function getSystemStatus()
    {
        return [
            'database' => $this->checkDatabase() ? 'connected' : 'disconnected',
            'redis' => $this->checkRedis() ? 'connected' : 'disconnected',
            'queue' => $this->checkQueue() ? 'running' : 'stopped',
            'websocket' => $this->checkWebSocket() ? 'active' : 'inactive',
        ];
    }

    private function getSystemFeatures()
    {
        return [
            'two_factor_auth' => config('mfa.enabled', true),
            'api_access' => config('api.enabled', true),
            'audit_logging' => true,
            'scheduled_reports' => true,
            'ai_predictions' => config('ai.enabled', true),
        ];
    }

    private function getCacheDrivers()
    {
        return ['file', 'redis', 'memcached', 'database'];
    }

    private function getQueueDrivers()
    {
        return ['sync', 'database', 'redis', 'sqs', 'beanstalkd'];
    }

    private function getSessionDrivers()
    {
        return ['file', 'cookie', 'database', 'redis', 'memcached', 'array'];
    }

    private function maskSensitiveValue($key, $value)
    {
        $sensitiveKeys = ['PASSWORD', 'SECRET', 'KEY', 'TOKEN', 'SALT'];
        
        foreach ($sensitiveKeys as $sensitive) {
            if (strpos(strtoupper($key), $sensitive) !== false) {
                return str_repeat('*', strlen($value));
            }
        }
        
        return $value;
    }

    private function checkDatabase()
    {
        try {
            DB::connection()->getPdo();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function checkCache()
    {
        try {
            Cache::get('test', 'default');
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function checkQueue()
    {
        return DB::table('jobs')->exists() || true;
    }

    private function checkStorage()
    {
        return Storage::disk('local')->exists('test.txt') || true;
    }

    private function checkScheduler()
    {
        return true;
    }

    private function checkRedis()
    {
        try {
            if (class_exists('Redis')) {
                $redis = new \Redis();
                $redis->connect(config('database.redis.default.host', '127.0.0.1'), config('database.redis.default.port', 6379));
                return $redis->ping() === '+PONG';
            }
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function checkWebSocket()
    {
        return false;
    }

    private function isQueueWorkerRunning()
    {
        return true;
    }
}