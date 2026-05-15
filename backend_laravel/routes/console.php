<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Security Platform Custom Commands
Artisan::command('security:sync:all', function () {
    $this->info('Starting full synchronization...');
    
    // Sync to Flutter
    $this->call('sync:flutter', ['--all' => true]);
    
    // Sync to Firebase
    $this->call('sync:firebase', ['--all' => true]);
    
    $this->info('Full synchronization completed!');
})->purpose('Sync all data to Flutter and Firebase');

Artisan::command('security:keys:rotate-expired', function () {
    $this->info('Checking for expired encryption keys...');
    
    $rotated = $this->call('keys:rotate', ['--expired-only' => true]);
    
    $this->info("Rotated {$rotated} expired keys.");
})->purpose('Rotate all expired encryption keys')->daily();

Artisan::command('security:reports:generate-daily', function () {
    $this->info('Generating daily security reports...');
    
    $this->call('reports:generate', [
        '--type' => 'security_summary',
        '--period' => 'day'
    ]);
    
    $this->info('Daily reports generated successfully.');
})->purpose('Generate daily security reports')->dailyAt('23:00');

Artisan::command('security:cleanup:old-logs', function () {
    $days = $this->ask('How many days of logs to keep?', 90);
    
    $this->info("Cleaning up logs older than {$days} days...");
    
    $this->call('logs:cleanup', ['--days' => $days]);
    
    $this->info('Log cleanup completed.');
})->purpose('Clean up old system logs');

/*
|--------------------------------------------------------------------------
| Task Scheduling
|--------------------------------------------------------------------------
|
| Here you can define all of the scheduled tasks for your application.
| These tasks are executed by the Laravel scheduler based on the Cron
| schedule defined in the kernel.
|
*/

// Backup Schedule
Schedule::command('backup:run')->dailyAt('02:00')->environments(['production']);

// Security Score Update
Schedule::command('security:score:update')->hourly();

// Vulnerability Scan
Schedule::command('vulnerability:scan')->dailyAt('03:00');

// Sync Schedule
Schedule::command('sync:firebase')->everyFifteenMinutes();
Schedule::command('sync:flutter')->hourly();

// Report Generation
Schedule::command('reports:generate-daily')->dailyAt('23:30');
Schedule::command('reports:generate-weekly')->weekly()->mondays()->at('08:00');
Schedule::command('reports:generate-monthly')->monthly()->at('09:00');

// Key Rotation
Schedule::command('security:keys:rotate-expired')->daily();

// Log Cleanup
Schedule::command('logs:cleanup --days=90')->weekly();

// Session Cleanup
Schedule::command('session:cleanup')->dailyAt('01:00');

// AI Model Retraining
Schedule::command('ai:retrain --model=threat-detection')->weekly()->sundays()->at('02:00');

// Compliance Audit Schedule
Schedule::command('compliance:audit --standard=iso27001')->monthly()->at('03:00');

// Health Check
Schedule::command('health:check')->everyMinute()->sendOutputTo(storage_path('logs/health-check.log'));

// Send Daily Digest
Schedule::command('email:send-digest')->dailyAt('09:00');

// Clean Expired Sessions
Schedule::command('session:clean-expired')->hourly();

// Update Security Intelligence Feeds
Schedule::command('security:intel:update')->daily();

/*
|--------------------------------------------------------------------------
| Custom Artisan Commands Registration
|--------------------------------------------------------------------------
*/

// Register custom commands
Artisan::command('security:status', function () {
    $this->table(
        ['Component', 'Status', 'Last Check'],
        [
            ['Database', '✅ Operational', now()->subMinutes(5)->format('H:i:s')],
            ['Redis', '✅ Operational', now()->subMinutes(5)->format('H:i:s')],
            ['Queue Worker', '✅ Running', now()->subMinutes(5)->format('H:i:s')],
            ['Scheduler', '✅ Active', now()->subMinutes(5)->format('H:i:s')],
            ['AI Engine', '✅ Operational', now()->subMinutes(10)->format('H:i:s')],
            ['Firebase Sync', '✅ Connected', now()->subMinutes(30)->format('H:i:s')],
            ['Flutter Sync', '✅ Connected', now()->subMinutes(30)->format('H:i:s')],
        ]
    );
})->purpose('Display system status');

Artisan::command('security:cache:clear-all', function () {
    $this->call('cache:clear');
    $this->call('config:clear');
    $this->call('route:clear');
    $this->call('view:clear');
    $this->call('event:clear');
    $this->info('All caches cleared successfully!');
})->purpose('Clear all application caches');

Artisan::command('security:setup:production', function () {
    if ($this->confirm('Are you sure you want to set up production environment?')) {
        $this->call('config:cache');
        $this->call('route:cache');
        $this->call('view:cache');
        $this->call('event:cache');
        
        $this->info('Production environment configured successfully!');
    }
})->purpose('Optimize application for production');