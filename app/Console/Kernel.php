<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Artisan;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * Uses in-process Artisan::call() instead of spawning shell subprocesses (proc_open)
     * so Laravel Scheduler works reliably on shared cPanel/LiteSpeed hosting.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // 1. Tự động kiểm tra và xuất bản các bài viết blog đã đến giờ hẹn (chạy mỗi phút)
        $schedule->call(function () {
            Artisan::call('blog:publish-scheduled');
        })->name('blog:publish-scheduled')->everyMinute()->withoutOverlapping(10);

        // 2. Tự động kiểm tra và kích hoạt các lịch tạo bài blog AI đến chu kỳ (chạy mỗi phút)
        $schedule->call(function () {
            Artisan::call('blog:run-automations');
        })->name('blog:run-automations')->everyMinute()->withoutOverlapping(15);
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
