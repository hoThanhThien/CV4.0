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
        $run = BlogAutomationRun::findOrFail($id);

        if (!$run->automation_id) {
            return back()->with('error', 'Lần chạy này không gắn với Automation cụ thể để thử lại.');
        }

        try {
            Artisan::call('blog:run-automations', [
                '--id' => $run->automation_id,
                '--force' => true,
            ]);

            return back()->with('success', "Đã kích hoạt thử lại lần chạy Automation #{$run->automation_id}!");
        } catch (Throwable $e) {
            return back()->with('error', 'Lỗi khi thử lại: ' . $e->getMessage());
        }
    }
}
