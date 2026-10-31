<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // 1. Tự động kiểm tra và xuất bản các bài viết blog đã đến giờ hẹn (chạy mỗi phút)
        $schedule->command('blog:publish-scheduled')
                 ->everyMinute()
                 ->withoutOverlapping(10)
                 ->runInBackground();

        // 2. Tự động kiểm tra và kích hoạt các lịch tạo bài blog AI đến chu kỳ (chạy mỗi 5 phút)
        $schedule->command('blog:run-automations')
                 ->everyFiveMinutes()
                 ->withoutOverlapping(15)
                 ->runInBackground();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
