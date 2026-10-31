<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BlogAutomation;
use App\Models\BlogAutomationRun;
use App\Services\AI\BlogContentGenerator;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Throwable;

class RunBlogAutomations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blog:run-automations {--id= : Chỉ định ID của Automation cụ thể} {--force : Bỏ qua điều kiện thời gian đến hạn}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Chạy các lịch tự động sinh bài viết AI theo lịch trình đã thiết lập';

    /**
     * Execute the console command.
     */
    public function handle(BlogContentGenerator $generator)
    {
        $id = $this->option('id');
        $force = $this->option('force');

        $query = BlogAutomation::query();
        if ($id) {
            $query->where('id', $id);
        } elseif (!$force) {
            $query->dueForRun();
        } else {
            $query->active();
        }

        $automations = $query->with(['contentConfig.provider'])->get();

        if ($automations->isEmpty()) {
            $this->info('Không có lịch tự động tạo bài nào đến hạn chạy.');
            return 0;
        }

        $this->info("Tìm thấy {$automations->count()} automation(s) cần xử lý.");

        foreach ($automations as $automation) {
            $lock = Cache::lock("blog_automation_lock_{$automation->id}", 600);
            if (!$lock->get()) {
                $this->warn("Automation #{$automation->id} ('{$automation->name}') đang được tiến trình khác xử lý. Bỏ qua.");
                continue;
            }

            try {
                $this->info("Đang xử lý Automation #{$automation->id}: '{$automation->name}'...");

                $config = $automation->contentConfig;
                if (!$config || !$config->is_active) {
                    $this->error("Cấu hình nội dung của Automation #{$automation->id} không khả dụng.");
                    continue;
                }

                $provider = $config->getEffectiveProvider();

                // Compute next run time immediately to prevent re-triggering by next minute's cron
                $nextRun = $automation->computeNextRunAt(now());
                $automation->update([
                    'last_run_at' => now(),
                    'next_run_at' => $nextRun,
                ]);

                // Create initial run log
                $runLog = BlogAutomationRun::create([
                    'automation_id' => $automation->id,
                    'content_config_id' => $config->id,
                    'ai_provider_id' => $provider ? $provider->id : null,
                    'provider_name' => $provider ? $provider->name : 'Unknown',
                    'model_used' => $config->getEffectiveModel(),
                    'topic_processed' => $config->main_topic ?: 'Auto Topic',
                    'articles_requested' => $automation->articles_per_run,
                    'articles_created' => 0,
                    'articles_failed' => 0,
                    'status' => 'running',
                    'started_at' => now(),
                ]);

                $articlesToCreate = max(1, min(5, (int)$automation->articles_per_run));
                $intervalMinutes = max(15, (int)$automation->interval_minutes);

                for ($i = 0; $i < $articlesToCreate; $i++) {
                    $targetScheduledAt = null;
                    if ($automation->publishing_mode === 'auto_schedule') {
                        $targetScheduledAt = now()->addMinutes(($i + 1) * $intervalMinutes);
                    }

                    $this->line("-> Đang sinh bài viết " . ($i + 1) . "/{$articlesToCreate}...");

                    $result = $generator->generatePost(
                        $config,
                        $automation->publishing_mode,
                        $targetScheduledAt,
                        $runLog
                    );

                    if ($result['success']) {
                        $post = $result['post'];
                        $this->info("   ✓ Đã tạo thành công bài viết: [#{$post->id}] '{$post->title}' (Trạng thái: {$post->status})");
                    } else {
                        $this->error("   ✗ Thất bại: " . $result['error']);
                    }
                }

                // Finalize run log
                $runLog->refresh();
                if ($runLog->articles_created === 0) {
                    $runLog->update(['status' => 'failed', 'completed_at' => now()]);
                } elseif ($runLog->articles_failed > 0) {
                    $runLog->update(['status' => 'partially_completed', 'completed_at' => now()]);
                } else {
                    $runLog->update(['status' => 'completed', 'completed_at' => now()]);
                }

                $this->info("Hoàn tất Automation #{$automation->id}. Lần chạy tiếp theo: " . ($nextRun ? $nextRun->toDateTimeString() . ' UTC' : 'None'));
            } catch (Throwable $e) {
                $this->error("Lỗi ngoại lệ khi chạy Automation #{$automation->id}: " . $e->getMessage());
            } finally {
                $lock->release();
            }
        }

        return 0;
    }
}
