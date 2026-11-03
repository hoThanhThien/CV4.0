<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogAutomationRun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Throwable;

class BlogAutomationLogController extends Controller
{
    public function index()
    {
        $runs = BlogAutomationRun::with(['automation', 'contentConfig'])->latest()->paginate(15);
        return view('admin.ai.logs.index', compact('runs'));
    }

    public function show($id)
    {
        $run = BlogAutomationRun::with(['automation', 'contentConfig', 'provider'])->findOrFail($id);
        return view('admin.ai.logs.show', compact('run'));
    }

    public function retry($id)
    {
        @set_time_limit(300);

        $run = BlogAutomationRun::findOrFail($id);

        if (!$run->automation_id && $run->content_config_id) {
            $config = \App\Models\AiContentConfig::find($run->content_config_id);
            if (!$config) {
                return back()->with('error', 'Cấu hình nội dung gốc của lượt chạy này đã bị xóa.');
            }
            return app(\App\Http\Controllers\Admin\AIContentConfigController::class)
                ->generateNow($config->id, app(\App\Services\AI\BlogContentGenerator::class));
        }

        if (!$run->automation_id) {
            return back()->with('error', 'Lần chạy này không gắn với Automation cụ thể để thử lại.');
        }

        try {
            Artisan::call('blog:run-automations', [
                '--id' => $run->automation_id,
                '--force' => true,
            ]);

            $latestRun = BlogAutomationRun::where('automation_id', $run->automation_id)->latest('id')->first();
            if ($latestRun && $latestRun->status === 'failed') {
                return back()->with('error', 'Thử lại thất bại: ' . ($latestRun->error_details ?: 'Kiểm tra chi tiết lỗi.'));
            }

            return redirect()->route('admin.ai.logs.index')->with('success', "Đã thử lại thành công Automation #{$run->automation_id}!");
        } catch (Throwable $e) {
            return back()->with('error', 'Lỗi khi thử lại: ' . $e->getMessage());
        }
    }
}
