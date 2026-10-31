<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BlogPost;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Throwable;

class PublishScheduledBlogPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blog:publish-scheduled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kiểm tra và tự động xuất bản các bài viết blog đã đến thời gian hẹn';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $lock = Cache::lock('publish_scheduled_posts_lock', 50);

        if (!$lock->get()) {
            $this->warn('Một tiến trình xuất bản bài viết khác đang chạy. Bỏ qua lượt này.');
            return 0;
        }

        try {
            $postsToPublish = BlogPost::dueForPublishing()->get();

            if ($postsToPublish->isEmpty()) {
                $this->info('Không có bài viết nào cần xuất bản tại thời điểm ' . now()->toDateTimeString());
                return 0;
            }

            $count = 0;
            foreach ($postsToPublish as $post) {
                DB::beginTransaction();
                try {
                    // Double check with atomic lock
                    $affected = BlogPost::where('id', $post->id)
                        ->where('status', BlogPost::STATUS_SCHEDULED)
                        ->update([
                            'status' => BlogPost::STATUS_PUBLISHED,
                            'published' => true,
                            'published_at' => now(),
                        ]);

                    if ($affected > 0) {
                        DB::commit();
                        $count++;
                        $this->info("✓ Đã xuất bản thành công: [#{$post->id}] '{$post->title}'");
                    } else {
                        DB::rollBack();
                    }
                } catch (Throwable $e) {
                    DB::rollBack();
                    $this->error("✗ Lỗi khi xuất bản bài [#{$post->id}]: " . $e->getMessage());
                }
            }

            $this->info("Hoàn tất! Tổng cộng đã xuất bản {$count} bài viết.");
            return 0;
        } finally {
            $lock->release();
        }
    }
}
