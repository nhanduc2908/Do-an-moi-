<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Các Artisan commands đã đăng ký
     *
     * @var array
     */
    protected $commands = [
        Commands\InstallSecuritySystem::class,
        Commands\Create10RolesCommand::class,
        Commands\Create25DomainsCommand::class,
        Commands\Create600CriteriaCommand::class,
        Commands\GenerateMasterKeysCommand::class,
        Commands\SyncFlutterDataCommand::class,
        Commands\AIGenerateCriteriaCommand::class,
        Commands\SendDailySecurityReport::class,
        Commands\CleanExpiredSessionsCommand::class,
        Commands\BackupDatabaseCommand::class,
        Commands\RotateEncryptionKeysCommand::class,
        Commands\RunVulnerabilityScanCommand::class,
        Commands\TestKeyVerificationCommand::class,
    ];

    /**
     * Định nghĩa lịch trình cho các commands
     */
    protected function schedule(Schedule $schedule): void
    {
        // Báo cáo bảo mật hàng ngày lúc 8:00 AM
        $schedule->command('security:daily-report')
                 ->dailyAt('08:00')
                 ->emailOutputOnFailure(config('security.admin_email'))
                 ->appendOutputTo(storage_path('logs/security-reports.log'));

        // Dọn dẹp session hết hạn mỗi giờ
        $schedule->command('security:clean-sessions')
                 ->hourly()
                 ->withoutOverlapping();

        // Sao lưu database hàng ngày lúc 2:00 AM
        $schedule->command('db:backup')
                 ->dailyAt('02:00')
                 ->onFailure(function () {
                     \Log::error('Database backup failed!');
                 });

        // Xoay vòng khóa mã hóa mỗi tháng (ngày 1 lúc 3:00 AM)
        $schedule->command('keys:rotate')
                 ->monthlyOn(1, '03:00')
                 ->before(function () {
                     \Log::info('Starting key rotation process...');
                 });

        // Quét lỗ hổng bảo mật mỗi Chủ Nhật lúc 3:00 AM
        $schedule->command('security:vulnerability-scan')
                 ->weekly()
                 ->sundays()
                 ->at('03:00')
                 ->emailOutputTo(config('security.security_team_email'));

        // Đồng bộ dữ liệu Flutter mỗi 15 phút
        $schedule->command('sync:flutter-data')
                 ->everyFifteenMinutes()
                 ->withoutOverlapping(5);

        // Kiểm tra AI criteria mỗi ngày lúc 1:00 AM
        $schedule->command('criteria:ai-generate')
                 ->dailyAt('01:00')
                 ->when(function () {
                     return config('security.auto_ai_generate', false);
                 });

        // Xác thực keys mỗi ngày lúc 4:00 AM
        $schedule->command('keys:test-verification')
                 ->dailyAt('04:00')
                 ->emailOutputOnFailure(config('security.security_team_email'));

        // Backup database mỗi Chủ Nhật (backup đầy đủ)
        $schedule->command('db:backup --full')
                 ->weekly()
                 ->sundays()
                 ->at('01:00')
                 ->appendOutputTo(storage_path('logs/weekly-backup.log'));
    }

    /**
     * Đăng ký các commands
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

    /**
     * Lấy các commands schedule cho ứng dụng
     * 
     * @return array
     */
    protected function scheduleCommands()
    {
        // Custom schedule commands
        return [
            'security:daily-report' => [
                'description' => 'Gửi báo cáo bảo mật hàng ngày',
                'frequency' => 'dailyAt(08:00)'
            ],
            'security:clean-sessions' => [
                'description' => 'Xóa session hết hạn',
                'frequency' => 'hourly()'
            ],
            'db:backup' => [
                'description' => 'Sao lưu cơ sở dữ liệu',
                'frequency' => 'dailyAt(02:00)'
            ],
            'keys:rotate' => [
                'description' => 'Xoay vòng khóa mã hóa',
                'frequency' => 'monthlyOn(1, 03:00)'
            ],
            'security:vulnerability-scan' => [
                'description' => 'Quét lỗ hổng bảo mật',
                'frequency' => 'weekly()->sundays()->at(03:00)'
            ],
            'sync:flutter-data' => [
                'description' => 'Đồng bộ dữ liệu Flutter',
                'frequency' => 'everyFifteenMinutes()'
            ]
        ];
    }
}